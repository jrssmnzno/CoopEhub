<div class="loan-summary">
    <h5 class="mb-4" style="color: #0d6efd;"><i class="fas fa-calculator"></i> Loan Summary Calculator</h5>

    <div class="row">
        <div class="col-md-6">
            <div class="loan-input-group">
                <label class="loan-input-label">Loan Amount (₱)</label>
                <input 
                    type="number" 
                    id="loanAmount" 
                    class="loan-input" 
                    placeholder="0.00"
                    step="100"
                    min="0"
                >
                <small class="text-muted d-block mt-2">Principal amount to be borrowed</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="loan-input-group">
                <label class="loan-input-label">Interest Rate (% per annum)</label>
                <input 
                    type="number" 
                    id="interestRate" 
                    class="loan-input" 
                    placeholder="0.00"
                    step="0.1"
                    min="0"
                    max="100"
                >
                <small class="text-muted d-block mt-2">Annual interest rate</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="loan-input-group">
                <label class="loan-input-label">Loan Term (Years)</label>
                <input 
                    type="number" 
                    id="loanTerm" 
                    class="loan-input" 
                    placeholder="0"
                    step="1"
                    min="0"
                    max="30"
                >
                <small class="text-muted d-block mt-2">Duration of the loan in years</small>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="loan-result">
                <div>
                    <div class="loan-result-label">Monthly Installment</div>
                </div>
                <div class="loan-result-value" id="monthlyInstallment">₱0.00</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="loan-result">
                <div>
                    <div class="loan-result-label">Total Repayment</div>
                </div>
                <div class="loan-result-value" id="totalRepayment">₱0.00</div>
            </div>
        </div>
    </div>

    <p class="text-muted mt-4 mb-0">
        <small>
            <strong>Note:</strong> This is a real-time calculation. Values update automatically as you enter loan details. Final amounts may vary based on payment frequency and adjustments.
        </small>
    </p>
</div>
