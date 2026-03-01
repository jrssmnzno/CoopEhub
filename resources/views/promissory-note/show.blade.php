@extends('layouts.app')

@section('title', 'Promissory Note')
@section('subtitle', 'Document for Loan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3 no-print">
        <button class="btn btn-primary" onclick="printPromissoryNote()">
            <i class="fas fa-print"></i> Print Document
        </button>
    </div>

    <div id="promissoryNoteContent">
        @include('promissory-note.template')
    </div>
</div>
@endsection
