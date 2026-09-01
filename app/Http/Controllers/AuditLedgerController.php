<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
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
            ->through(function ($log) {
                return (object)[
                    'timestamp' => $log->created_at->setTimezone('Asia/Manila')->format('M d, Y • h:i A'),
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
     * Filter audit logs
     */
    public function filter(Request $request): JsonResponse
    {
        $query = AuditLog::with('user');
        
        // Filter by search term (user, activity, module, reference)
        if ($request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhere('activity', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filter by activity type
        if ($request->input('activity')) {
            $query->where('activity', $request->input('activity'));
        }
        
        // Filter by date
        if ($request->input('date')) {
            $date = $request->input('date');
            $query->whereDate('created_at', $date);
        }
        
        $audits = $query->latest()
            ->paginate(20)
            ->through(function ($log) {
                return (object)[
                    'timestamp' => $log->created_at->setTimezone('Asia/Manila')->format('M d, Y • h:i A'),
                    'user' => $log->user?->name ?? 'System',
                    'activity' => str_replace('_', ' ', ucfirst($log->activity)),
                    'module' => $log->subject_type ?? 'System',
                    'reference' => $log->subject_id ?? 'N/A',
                    'details' => $log->notes ?? $log->activity,
                    'ip' => $log->ip_address,
                ];
            });
        
        return response()->json([
            'audits' => $audits->items(),
            'pagination' => [
                'current_page' => $audits->currentPage(),
                'per_page' => $audits->perPage(),
                'total' => $audits->total(),
                'last_page' => $audits->lastPage(),
            ]
        ]);
    }

    /**
     * Export audit log to CSV.
     */
    public function export(Request $request): Response
    {
        $filename = 'audit_log_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');
        
        // Write CSV header
        fputcsv($handle, ['Timestamp', 'User', 'Activity', 'Module', 'Reference', 'Details', 'IP Address']);

        // Get real audit logs from database
        $audits = AuditLog::with('user')
            ->latest()
            ->get();

        // Write data rows
        foreach ($audits as $audit) {
            fputcsv($handle, [
                $audit->created_at->setTimezone('Asia/Manila')->format('M d, Y • h:i A'),
                $audit->user?->name ?? 'System',
                str_replace('_', ' ', ucfirst($audit->activity)),
                $audit->subject_type ?? 'System',
                $audit->subject_id ?? 'N/A',
                $audit->notes ?? $audit->activity,
                $audit->ip_address ?? 'N/A',
            ]);
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
