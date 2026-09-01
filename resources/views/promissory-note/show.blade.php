@extends('layouts.app')

@section('title', 'Promissory Note')
@section('subtitle', 'Document for Loan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3 no-print">
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <button class="btn btn-primary" onclick="printPromissoryNote()">
            <i class="fas fa-print"></i> Print Document
        </button>
    </div>

    <div id="promissoryNoteContent">
        @include('promissory-note.template')
    </div>
</div>
@endsection
