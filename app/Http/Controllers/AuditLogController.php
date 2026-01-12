<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AuditLogController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if($user && $user->isReadOnly()){
            abort(403, 'Read-only access');
        }

        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
