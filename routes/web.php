<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReceiptLogController;
use App\Http\Controllers\LoanPortfolioController;
use App\Http\Controllers\AuditLedgerController;
use App\Http\Controllers\PromissoryNoteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\Admin\LoanRequestController as AdminLoanRequestController;
use App\Http\Controllers\AttendanceKioskController;
use App\Http\Controllers\Admin\MeetingManagementController;

// Landing page / Login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('welcome');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Validation Routes (AJAX)
Route::post('/validate-member-id', [RegisterController::class, 'validateMemberId'])->name('validate-member-id');
Route::post('/check-password-strength', [RegisterController::class, 'checkPasswordStrength'])->name('check-password-strength');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Members CRUD
        Route::resource('members', MemberController::class);
        
        // Receipt Log
        Route::get('/receipt-log', [ReceiptLogController::class, 'index'])->name('receipt-log.index');
        Route::post('/receipt-log/export', [ReceiptLogController::class, 'export'])->name('receipt-log.export');
        Route::get('/receipt-log/{receipt}', [ReceiptLogController::class, 'show'])->name('receipt-log.show');
        
        // Loan Portfolio / SOA
        Route::get('/loan-portfolio', [LoanPortfolioController::class, 'index'])->name('loan-portfolio.index');
        Route::get('/loan-portfolio/member/{member}', [LoanPortfolioController::class, 'showMember'])->name('loan-portfolio.member');
        Route::post('/loan-portfolio/soa', [LoanPortfolioController::class, 'generateSOA'])->name('loan-portfolio.soa');
        Route::post('/loan-portfolio/export', [LoanPortfolioController::class, 'export'])->name('loan-portfolio.export');
        
        // Audit Ledger
        Route::get('/audit-ledger', [AuditLedgerController::class, 'index'])->name('audit-ledger.index');
        Route::post('/audit-ledger/export', [AuditLedgerController::class, 'export'])->name('audit-ledger.export');
    });
    
    // Promissory Note
    Route::get('/promissory-note/{loan}', [PromissoryNoteController::class, 'show'])->name('promissory-note.show');
    Route::post('/promissory-note/{loan}/print', [PromissoryNoteController::class, 'print'])->name('promissory-note.print');
    Route::post('/promissory-note/{loan}/pdf', [PromissoryNoteController::class, 'pdf'])->name('promissory-note.pdf');
    
    // Payment Processing
    Route::post('/payments/loan/{loan}/process', [\App\Http\Controllers\PaymentController::class, 'processPayment'])->name('payment.process');
    Route::post('/loans/{loan}/topup', [\App\Http\Controllers\PaymentController::class, 'topUpLoan'])->name('loan.topup');

    // Attendance Kiosk Routes
    Route::get('/attendance/kiosk/{meeting}', [AttendanceKioskController::class, 'index'])->name('attendance.kiosk.show');
    Route::post('/attendance/kiosk/{meeting}/submit', [AttendanceKioskController::class, 'submit'])->name('attendance.kiosk.submit');

    // API Routes for Dashboard
    Route::prefix('api')->group(function () {
        Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('api.dashboard.data');
        Route::post('/dashboard/calculate-preview', [DashboardController::class, 'calculateLoanPreview'])->name('api.dashboard.calculate');
        
        // Loan Request API
        Route::post('/loan-requests', [LoanRequestController::class, 'store'])->name('api.loan-requests.store');
        Route::get('/loan-requests', [LoanRequestController::class, 'myRequests'])->name('api.loan-requests.my');

        // Attendance API
        Route::get('/attendance/meeting/{meeting}/details', [AttendanceKioskController::class, 'getMeetingDetails'])->name('api.attendance.meeting.details');
    });

    // Admin Routes
    Route::middleware(['can:viewAll,App\Models\LoanRequest'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/loan-requests', [AdminLoanRequestController::class, 'index'])->name('loan-requests.index');
        
        // Meeting Management Routes
        Route::resource('meetings', MeetingManagementController::class);
        Route::post('/meetings/{meeting}/open-attendance', [MeetingManagementController::class, 'openAttendance'])->name('meetings.openAttendance');
        Route::post('/meetings/{meeting}/close-attendance', [MeetingManagementController::class, 'closeAttendance'])->name('meetings.closeAttendance');
        Route::post('/meetings/{meeting}/mark-excused', [MeetingManagementController::class, 'markExcused'])->name('meetings.markExcused');
        
        // Admin API Routes
        Route::prefix('api/loan-requests')->group(function () {
            Route::get('/pending', [AdminLoanRequestController::class, 'getPending'])->name('loan-requests.pending');
            Route::get('/{loanRequest}', [AdminLoanRequestController::class, 'show'])->name('loan-requests.show');
            Route::post('/{loanRequest}/approve', [AdminLoanRequestController::class, 'approve'])->name('loan-requests.approve');
            Route::post('/{loanRequest}/reject', [AdminLoanRequestController::class, 'reject'])->name('loan-requests.reject');
        });
    });
});
