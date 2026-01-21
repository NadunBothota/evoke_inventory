<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\DashboardReportMail;
use Illuminate\Support\Facades\Mail;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function create()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

        return view('admin.reports.create', compact('admins'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient'   => 'required|email',
            'report_type' => 'required|in:pdf,excel',
        ]);

        try {
            $recipient  = $request->input('recipient');
            $reportType = $request->input('report_type');

            // 1. Prepare inventory data for email body
            $inventoryData = Item::select(
                    'categories.name as name',
                    DB::raw('COUNT(items.id) AS item_count'),
                    DB::raw('SUM(items.value) AS total_value')
                )
                ->join('categories', 'items.category_id', '=', 'categories.id')
                ->groupBy('categories.name')
                ->orderBy('categories.name')
                ->get();

            $attachmentData = null;
            $fileName       = '';
            $mimeType       = '';

            // 2. Prepare report attachment
            if ($reportType === 'pdf') {
                $items = Item::all();

                $attachmentData = Pdf::loadView(
                    'admin.inventory_pdf',
                    compact('items')
                )->output();

                $fileName = 'inventory_report.pdf';
                $mimeType = 'application/pdf';

            } elseif ($reportType === 'excel') {
                $attachmentData = Excel::raw(
                    new ItemsExport(),
                    \Maatwebsite\Excel\Excel::XLSX
                );

                $fileName = 'inventory_report.xlsx';
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            }

            // 3. Send email
            Mail::to($recipient)->send(
                new DashboardReportMail(
                    $attachmentData,
                    $fileName,
                    $mimeType,
                    $inventoryData
                )
            );

            // 4. Redirect on success
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Report sent successfully!');

        } catch (\Exception $e) {

            // 5. Log detailed error
            Log::error(
                'Error sending report: ' . $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Could not send the report. An unexpected error occurred. Please check the application logs.'
                );
        }
    }
}
