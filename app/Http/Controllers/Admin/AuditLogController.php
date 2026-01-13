<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AuditLogsExport;
use App\Models\AuditLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends Controller
{
    private function denyReadOnly(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isReadOnly()) {
            abort(403, 'Read-only access');
        }
    }

    public function index()
    {
        $auditLogs = AuditLog::with('user')->latest()->paginate(15);
        return view('admin.audit-logs.index', compact('auditLogs'));
    }

    public function exportExcel()
    {
        $this->denyReadOnly();

        return Excel::download(new AuditLogsExport(), 'audit-logs.xlsx');
    }

    public function exportPdf()
    {
        $this->denyReadOnly();

        $auditLogs = AuditLog::with('user')->get();
        $pdf = PDF::loadView('exports.audit-logs-pdf', compact('auditLogs'));

        return $pdf->download('audit-logs.pdf');
    }
}
