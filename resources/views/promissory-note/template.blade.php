<div class="promissory-note" style="font-family: 'Calibri', 'Arial', sans-serif; padding: 2rem; max-width: 900px; margin: 0 auto;">
    
    <!-- Document Header -->
    <div style="text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #333; padding-bottom: 1rem;">
        <h1 style="margin: 0; font-size: 1.8rem; font-weight: bold;">PROMISSORY NOTE</h1>
        <p style="margin: 0.5rem 0 0 0; font-size: 0.95rem; color: #555;">Coop eHub Cooperative</p>
    </div>

    <!-- Document Information -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between;">
        <div>
            <p style="margin: 0.25rem 0; font-size: 0.95rem;">
                <strong>Date of Issue:</strong> <span id="pn-date" style="font-weight: normal;"></span>
            </p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0.25rem 0; font-size: 0.95rem;">
                <strong>Promissory Note No.:</strong> <span id="pn-number" style="font-weight: normal;"></span>
            </p>
        </div>
    </div>

    <!-- Legal Body -->
    <div style="line-height: 1.8; text-align: justify; margin-bottom: 2rem;">
        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            KNOW ALL MEN BY THESE PRESENTS that I/WE, <strong><span id="pn-borrower">_____________</span></strong>, with postal address at <strong><span id="pn-address">_____________</span></strong>, hereby unconditionally promise to pay to the order of the <strong>COOP EHUB COOPERATIVE</strong>, the sum of <strong><span id="pn-principal-words">_____________</span></strong> (Philippine Currency: <strong><span id="pn-principal">₱_____________</span></strong>) without demand or notice, on or before the dates specified in the Repayment Schedule hereinbelow.
        </p>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            1. This note has been issued for value received as a LOAN. The principal amount shall bear interest at the rate of <strong><span id="pn-interest">0</span>% per annum</strong>, computed on the declining balance method, payable monthly in fixed installments of <strong><span id="pn-monthly">₱0.00</span></strong> for a period of <strong><span id="pn-term">0</span> months</strong>, as detailed in the Repayment Schedule below.
        </p>

        <!-- Repayment Schedule -->
        <p style="margin-bottom: 0.75rem; font-weight: bold; text-align: center;">REPAYMENT SCHEDULE</p>
        
        <table id="pn-schedule-table" style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: 0.9rem;">
            <thead>
                <tr style="background-color: #e9ecef;">
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">INSTALLMENT No.</th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">DUE DATE</th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">PRINCIPAL</th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">INTEREST</th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">TOTAL AMOUNT</th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;">STATUS</th>
                </tr>
                <tr style="background-color: #e9ecef;">
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                    <th style="border: 1px solid #000; padding: 0.75rem; text-align: center; font-weight: bold;"></th>
                </tr>
            </thead>
            <tbody id="pn-schedule-body">
                <!-- Populated by JavaScript -->
            </tbody>
        </table>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            2. Payment shall be made to COOP EHUB COOPERATIVE or its duly authorized representative at their principal office or at such other places as the Cooperative may designate. Each payment shall be applied first to interest accrued and the remainder to the principal balance.
        </p>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            3. In the event of default in payment of any installment or any obligation hereunder, the entire outstanding balance of this note, including all accrued interest, shall immediately become due and payable WITHOUT DEMAND, PROTEST, OR NOTICE OF ANY KIND, at the option of the creditor.
        </p>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            4. The undersigned hereby waives presentment, protest, and notice of dishonor. The maker expressly acknowledges that the Cooperative will rely solely on this note as security and that the maker is waiving all defenses that may be available by law.
        </p>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            5. This Promissory Note shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions. The maker hereby submits himself/herself to the jurisdiction of the appropriate courts in the Philippines.
        </p>

        <p style="margin-bottom: 1.5rem; text-indent: 2rem;">
            IN WITNESS WHEREOF, I/WE have hereunto set my/our hand this <span id="pn-day">____</span> day of <span id="pn-month">____</span>, <span id="pn-year">____</span>.
        </p>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between;">
            <!-- Borrower Signature -->
            <div style="flex: 1; text-align: center;">
                <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto; height: 60px;"></div>
                <p style="margin: 0.5rem 0 0 0; font-weight: bold; font-size: 0.9rem;">BORROWER'S SIGNATURE</p>
                <div id="pn-borrower-sig" style="margin-top: 0.5rem; font-size: 0.9rem; font-weight: bold;">______________________</div>
                <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">Signature over Printed Name</p>
            </div>

            <!-- Witness Signature -->
            <div style="flex: 1; text-align: center;">
                <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto; height: 60px;"></div>
                <p style="margin: 0.5rem 0 0 0; font-weight: bold; font-size: 0.9rem;">WITNESS</p>
                <div style="margin-top: 2rem; font-size: 0.9rem;">_____________________________</div>
                <p style="margin-top: 0.25rem; font-size: 0.85rem; color: #666;">Name and Signature</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 3rem; padding-top: 1rem; border-top: 1px solid #ccc; text-align: center; font-size: 0.8rem; color: #666;">
        <p style="margin: 0;">This is an official document of COOP EHUB COOPERATIVE | Issued Date: <span id="pn-footer-date">MM/DD/YYYY</span></p>
    </div>

</div>

<style>
    .promissory-note {
        background: white;
        color: #000;
    }

    .promissory-note p {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    @media print {
        .promissory-note {
            padding: 0;
            margin: 0;
        }
        
        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
