<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInstallmentPayment extends Model
{
    protected $fillable = [
        'transaction_id',
        'loan_installment_id',
        'principal_amount',
        'interest_amount',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
    ];

    public function installment()
    {
        return $this->belongsTo(LoanInstallment::class, 'loan_installment_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
