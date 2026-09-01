<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ReceiptLogController extends Controller
{
    /**
     * Display the receipt log.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with('member', 'loan');

        // Filter by search term (member name, amount, or date)
        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', function ($memberQuery) use ($search) {
                    $memberQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('total_amount', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Filter by transaction type
        if ($request->has('type') && $request->input('type')) {
            $type = $request->input('type');
            $query->where('type', $type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->input('date_from')) {
            $dateFrom = $request->input('date_from');
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($request->has('date_to') && $request->input('date_to')) {
            $dateTo = $request->input('date_to');
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $transactionsPaginated = $query->latest()->paginate(15);

        $receipts = $transactionsPaginated->map(function ($trans) {
            return (object)[
                'id' => $trans->id,
                'number' => 'RCP-' . str_pad($trans->id, 5, '0', STR_PAD_LEFT),
                'member' => $trans->member?->full_name ?? 'N/A',
                'type' => str_replace('_', ' ', ucfirst($trans->type)),
                'amount' => $trans->total_amount,
                'date' => $trans->created_at->format('Y-m-d'),
                'reference' => $trans->loan?->loan_number ?? $trans->reference_number ?? 'N/A',
                'status' => 'completed',
                'created_at' => $trans->created_at,
            ];
        });

        return view('receipt-log.index', compact('receipts', 'transactionsPaginated'));
    }

    /**
     * Show receipt details.
     */
    public function show($id): View
    {
        $transaction = Transaction::with('member', 'loan')->findOrFail($id);

        $receipt = (object)[
            'id' => $transaction->id,
            'number' => 'RCP-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT),
            'member' => $transaction->member?->full_name ?? 'N/A',
            'type' => str_replace('_', ' ', ucfirst($transaction->type)),
            'amount' => $transaction->total_amount,
            'date' => $transaction->created_at->format('Y-m-d'),
            'reference' => $transaction->loan?->loan_number ?? $transaction->reference_number ?? 'N/A',
            'status' => 'completed',
            'notes' => $transaction->notes ?? 'No notes',
        ];

        return view('receipt-log.show', compact('receipt'));
    }

    /**
     * Export receipts to CSV.
     */
    public function export(Request $request): Response
    {
        $receipts = Transaction::with('member', 'loan')
            ->latest()
            ->get()
            ->map(function ($trans) {
                return [
                    'RCP-' . str_pad($trans->id, 5, '0', STR_PAD_LEFT),
                    $trans->member?->full_name ?? 'N/A',
                    str_replace('_', ' ', ucfirst($trans->type)),
                    $trans->total_amount,
                    $trans->created_at->format('Y-m-d'),
                    $trans->loan?->loan_number ?? $trans->reference_number ?? 'N/A',
                    'Completed',
                ];
            });

        $filename = 'receipt_log_' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, ['Receipt Number', 'Member', 'Type', 'Amount', 'Date', 'Reference', 'Status']);

        foreach ($receipts as $receipt) {
            fputcsv($handle, $receipt);
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
