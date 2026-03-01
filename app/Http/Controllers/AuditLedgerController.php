<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AuditLedgerController extends Controller
{
    /**
     * Display the audit ledger.
     */
    public function index(): View
    {
        $audits = AuditLog::with('user')
            ->latest()
            ->paginate(20)
            ->map(function ($log) {
                return (object)[
                    'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
                    'user' => $log->user?->name ?? 'System',
                    'activity' => str_replace('_', ' ', ucfirst($log->activity)),
                    'module' => $log->subject_type ?? 'System',
                    'reference' => $log->subject_id ?? 'N/A',
                    'details' => $log->notes ?? $log->activity,
                    'ip' => $log->ip_address,
                ];
            });

        // Statistics
        $statistics = [
            'logins' => AuditLog::where('activity', 'login')->count(),
            'creates' => AuditLog::where('activity', 'create')->count(),
            'updates' => AuditLog::where('activity', 'update')->count(),
            'reports' => AuditLog::where('activity', 'like', '%export%')->count(),
        ];

        // Most active users (last 30 days)
        $mostActiveUsers = AuditLog::with('user')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_id')
            ->selectRaw('user_id, count(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return (object)[
                    'user' => $log->user?->name ?? 'System',
                    'count' => $log->count,
                ];
            });

        return view('audit-ledger.index', compact('audits', 'statistics', 'mostActiveUsers'));
    }

    /**
     * Export audit log to CSV.
     */
    public function export(Request $request): Response
    {
        $filename = 'audit_log_' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, ['Timestamp', 'User', 'Activity', 'Module', 'Reference', 'Details', 'IP Address']);

        $audits = [
            ['2024-01-20 14:30:00', 'Admin User', 'Create', 'Members', 'MBR-001', 'New member registered', '192.168.1.100'],
            ['2024-01-20 13:45:00', 'Manager User', 'Update', 'Loans', 'LN-2024-001', 'Loan status updated', '192.168.1.101'],
        ];

        foreach ($audits as $audit) {
            fputcsv($handle, $audit);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
