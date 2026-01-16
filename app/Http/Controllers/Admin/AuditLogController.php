<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request; // Import Request
use Illuminate\Support\Facades\Auth;
use App\Exports\AuditLogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditLogController extends Controller
{
    public function index(Request $request) // Inject Request
    {
        /** @var User|null $user */
        $user = Auth::user();

        if($user && $user->isReadOnly()){
            abort(403, 'Read-only access');
        }

        // Start building the query
        $query = AuditLog::with(['user', 'item.category'])->latest();

        // Apply filters from the request
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('action', 'like', "%{$searchTerm}%")
                  ->orWhere('model', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhere('new_values', 'like', "%{$searchTerm}%")
                  ->orWhere('old_values', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // Paginate the results
        $auditLogs = $query->paginate(10)->withQueryString(); // withQueryString appends filters to pagination links

        // Get admin and super_admin users for the filter dropdown
        $users = User::whereIn('role', ['super_admin', 'admin'])->orderBy('name')->get();

        return view('admin.audit-logs.index', compact('auditLogs', 'users'));
    }

    public function exportExcel()
    {
        // Note: This export should also be updated to use filters in a real-world scenario
        return Excel::download(new AuditLogsExport, 'audit-logs.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = AuditLog::with(['user', 'item.category'])->latest();

        // Apply filters from the request
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('action', 'like', "%{$searchTerm}%")
                  ->orWhere('model', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhere('new_values', 'like', "%{$searchTerm}%")
                  ->orWhere('old_values', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $auditLogs = $query->get();

        $data = [
            'auditLogs' => $auditLogs,
            'companyName' => 'Evoke International (Pvt) Ltd',
            'companyAddress' => 'No 123, Colombo, Sri Lanka',
            'companyPhone' => '+94 11 123 4567',
            'companyEmail' => 'info@evotech.lk',
        ];

        $pdf = Pdf::loadView('exports.audit-logs-pdf', $data);
        return $pdf->download('Audit-Logs-Export-' . now()->format('Y-m-d') . '.pdf');
    }
}
