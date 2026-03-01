<div class="promissory-note">
    <div class="note-header">
        <h2 style="margin: 0; color: #0d6efd;">PROMISSORY NOTE</h2>
        <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-size: 0.9rem;">Coop eHub Cooperative</p>
    </div>

    <div class="note-content">
        <p style="margin-bottom: 1.5rem;">
            <strong>Date:</strong> {{ now()->format('F d, Y') }}<br>
            <strong>Note No.:</strong> PN-2024-001
        </p>

        <p style="text-align: justify; margin-bottom: 1.5rem;">
            FOR VALUE RECEIVED, the undersigned, <strong>JOHN SMITH</strong>, a resident of [ADDRESS], hereby promises to pay to the order of <strong>Coop eHub Cooperative</strong>, represented by its authorized officer, the sum of <strong>FORTY-FIVE THOUSAND PESOS ONLY (₱45,000.00)</strong> Philippine Currency, with interest at the rate of <strong>12% per annum</strong> from the date hereof, which shall be payable in installments as follows:
        </p>

        <p style="text-align: center; margin-bottom: 2rem;">
            <strong>Monthly Installment: ₱2,100.00</strong><br>
            <strong>Payment Period: 24 Months</strong>
        </p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
            <thead>
                <tr style="background-color: #f5f6f7;">
                    <th style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: left;">Due Date</th>
                    <th style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: right;">Amount</th>
                    <th style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: left;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem;">{{ now()->addMonth()->format('M d, Y') }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: right;">₱2,100.00</td>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem;">Pending</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem;">{{ now()->addMonths(2)->format('M d, Y') }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: right;">₱2,100.00</td>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem;">Pending</td>
                </tr>
                <tr style="background-color: #f8f9fa;">
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem; text-align: right;" colspan="2"><strong>Final Payment Due: {{ now()->addMonths(24)->format('M d, Y') }}</strong></td>
                    <td style="border: 1px solid #dee2e6; padding: 0.75rem;">₱2,100.00</td>
                </tr>
            </tbody>
        </table>

        <p style="text-align: justify; margin-bottom: 1.5rem;">
            All payments shall be made to [PAYMENT ADDRESS], during business hours. In case of default in payment of any installment on its due date, the whole amount of the principal note together with all accrued interests shall immediately become due and payable without notice or demand.
        </p>

        <p style="text-align: justify; margin-bottom: 1.5rem;">
            The undersigned hereby submits to the jurisdiction of the appropriate courts in [JURISDICTION] for any action or suit that may be brought to enforce the payment of this note.
        </p>

        <p style="text-align: justify; margin-bottom: 1.5rem;">
            This Promissory Note shall be governed by and construed in accordance with the laws of the Philippines.
        </p>
    </div>

    <div class="note-signature">
        <div class="signature-block">
            <div style="height: 3rem;"></div>
            <div class="signature-line">BORROWER'S SIGNATURE</div>
            <div style="margin-top: 0.5rem; font-size: 0.9rem;">John Smith</div>
        </div>
        <div class="signature-block">
            <div style="height: 3rem;"></div>
            <div class="signature-line">WITNESS</div>
            <div style="margin-top: 0.5rem; font-size: 0.9rem;">[Witness Name]</div>
        </div>
        <div class="signature-block">
            <div style="height: 3rem;"></div>
            <div class="signature-line">AUTHORIZED OFFICER</div>
            <div style="margin-top: 0.5rem; font-size: 0.9rem;">Coop eHub</div>
        </div>
    </div>
</div>
