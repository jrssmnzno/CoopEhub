@extends('layouts.app')

@section('title', 'Receipt Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('receipt-log.index') }}">Receipt Log</a></li>
            <li class="breadcrumb-item active">Receipt {{ $receipt->number }}</li>
        </ol>
    </nav>

    <!-- Receipt Details Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Receipt Details</h5>
                    <div>
                        <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button onclick="printReceipt()" class="btn btn-light btn-sm">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Receipt Number and Date -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Receipt Number</h6>
                            <p class="h5 font-monospace">{{ $receipt->number }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="text-muted">Date</h6>
                            <p class="h5">{{ $receipt->date }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Member Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Member</h6>
                            <p class="lead">{{ $receipt->member }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <p>
                                <span class="badge bg-success">{{ $receipt->status }}</span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Transaction Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Transaction Type</h6>
                            <p class="lead">{{ $receipt->type }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Reference</h6>
                            <p class="lead">{{ $receipt->reference }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Amount -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted">Amount</h6>
                            <p class="display-5 text-success fw-bold">₱{{ number_format($receipt->amount, 2) }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Notes -->
                    @if($receipt->notes !== 'No notes')
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted">Notes</h6>
                                <p class="card-text">{{ $receipt->notes }}</p>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <!-- Footer -->
                    <div class="row mt-5 text-center text-muted">
                        <div class="col-12">
                            <small>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .breadcrumb {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>

<script>
    function printReceipt() {
        window.print();
    }
</script>
@endsection
