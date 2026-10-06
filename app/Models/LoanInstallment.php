<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInstallment extends Model
{
    protected $fillable = [
        'installment_number',
        'due_date',
        'principal_due',
        'interest_due',
        'principal_paid',
        'interest_paid',
        'interest_accrued',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'interest_accrued' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanInstallmentPayment::class);
    }
}
