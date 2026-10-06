<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('installment_number');
            $table->date('due_date');
            $table->decimal('principal_due', 12, 2);
            $table->decimal('interest_due', 12, 2);
            $table->decimal('principal_paid', 12, 2)->default(0);
            $table->decimal('interest_paid', 12, 2)->default(0);
            $table->boolean('interest_accrued')->default(false);
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['loan_id', 'installment_number']);
            $table->index(['loan_id', 'due_date', 'status']);
        });

        Schema::create('loan_installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('loan_installment_id')->constrained('loan_installments')->cascadeOnDelete();
            $table->decimal('principal_amount', 12, 2)->default(0);
            $table->decimal('interest_amount', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['transaction_id', 'loan_installment_id'], 'installment_payment_unique');
        });

        $loans = DB::table('loans')
            ->where('running_balance', '>', 0)
            ->orderBy('id')
            ->get();

        foreach ($loans as $loan) {
            $balance = round((float) $loan->running_balance, 2);
            $rate = (float) $loan->interest_rate / 100 / 12;
            $nextDueDate = $loan->next_payment_date
                ? Carbon::parse($loan->next_payment_date)
                : Carbon::parse($loan->disbursement_date)->addMonthNoOverflow();
            $maturityDate = Carbon::parse($loan->maturity_date);
            $count = $nextDueDate->greaterThan($maturityDate)
                ? 1
                : max(1, min(
                    (int) $loan->term_months,
                    $nextDueDate->diffInMonths($maturityDate) + 1
                ));
            $monthlyPayment = $rate === 0.0
                ? $balance / $count
                : $balance * ($rate * pow(1 + $rate, $count)) / (pow(1 + $rate, $count) - 1);

            for ($offset = 0; $offset < $count && $balance > 0; $offset++) {
                $interest = round($balance * $rate, 2);
                $principal = $offset === $count - 1
                    ? $balance
                    : min($balance, max(0, round($monthlyPayment - $interest, 2)));

                DB::table('loan_installments')->insert([
                    'loan_id' => $loan->id,
                    'installment_number' => $offset + 1,
                    'due_date' => $nextDueDate->copy()->addMonthsNoOverflow($offset)->toDateString(),
                    'principal_due' => $principal,
                    'interest_due' => $interest,
                    'principal_paid' => 0,
                    'interest_paid' => 0,
                    'interest_accrued' => false,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $balance = round($balance - $principal, 2);
            }

            DB::table('loans')->where('id', $loan->id)->update([
                'monthly_payment' => round($monthlyPayment, 2),
                'payments_made' => 0,
                'interest_due' => DB::table('loan_installments')
                    ->where('loan_id', $loan->id)
                    ->whereDate('due_date', '<=', today()->toDateString())
                    ->sum('interest_due'),
            ]);

            if ($loan->status === 'active' && DB::table('loan_installments')
                ->where('loan_id', $loan->id)
                ->whereDate('due_date', '<', today()->toDateString())
                ->exists()) {
                DB::table('loans')->where('id', $loan->id)->update(['status' => 'overdue']);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_installment_payments');
        Schema::dropIfExists('loan_installments');
    }
};
