import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Loan Summary Calculator Component
class LoanCalculator {
    constructor() {
        this.init();
    }

    init() {
        const loanAmountEl = document.getElementById('loanAmount');
        const interestRateEl = document.getElementById('interestRate');
        const loanTermEl = document.getElementById('loanTerm');
        const monthlyInstallmentEl = document.getElementById('monthlyInstallment');
        const totalRepaymentEl = document.getElementById('totalRepayment');

        if (loanAmountEl && interestRateEl && loanTermEl) {
            [loanAmountEl, interestRateEl, loanTermEl].forEach(el => {
                el.addEventListener('input', () => this.calculate());
            });
        }
    }

    calculate() {
        const loanAmount = parseFloat(document.getElementById('loanAmount')?.value) || 0;
        const interestRate = parseFloat(document.getElementById('interestRate')?.value) || 0;
        const loanTerm = parseFloat(document.getElementById('loanTerm')?.value) || 0;

        if (loanAmount > 0 && interestRate > 0 && loanTerm > 0) {
            // Simple interest calculation
            const monthlyRate = interestRate / 100 / 12;
            const numPayments = loanTerm * 12;
            
            // Using formula: M = P * [r(1+r)^n] / [(1+r)^n - 1]
            const monthlyPayment = 
                (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, numPayments)) /
                (Math.pow(1 + monthlyRate, numPayments) - 1);
            
            const totalRepayment = monthlyPayment * numPayments;

            const monthlyEl = document.getElementById('monthlyInstallment');
            const totalEl = document.getElementById('totalRepayment');

            if (monthlyEl) {
                monthlyEl.textContent = this.formatCurrency(monthlyPayment);
            }
            if (totalEl) {
                totalEl.textContent = this.formatCurrency(totalRepayment);
            }
        }
    }

    formatCurrency(value) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(value);
    }
}

// Filter Functionality
class TableFilter {
    constructor(tableId, filterInputId) {
        this.table = document.getElementById(tableId);
        this.filterInput = document.getElementById(filterInputId);
        
        if (this.table && this.filterInput) {
            this.filterInput.addEventListener('keyup', (e) => this.filter(e));
            this.rows = this.table.querySelectorAll('tbody tr');
        }
    }

    filter(e) {
        const filterValue = e.target.value.toLowerCase();
        
        this.rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
}

// Page Animation Manager
class PageAnimations {
    constructor() {
        this.init();
    }

    init() {
        // Animate all cards on page load
        this.animateCards();
        
        // Animate summary cards with stagger
        this.animateSummaryCards();
        
        // Observe new elements for animation
        this.setupMutationObserver();
    }

    animateCards() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s forwards`;
        });
    }

    animateSummaryCards() {
        const summaryCards = document.querySelectorAll('.summary-card');
        summaryCards.forEach((card, index) => {
            card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.15}s forwards`;
        });
    }

    setupMutationObserver() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) {
                            if (node.classList && node.classList.contains('card')) {
                                node.style.animation = 'fadeInUp 0.6s ease-out forwards';
                            }
                        }
                    });
                }
            });
        });

        observer.observe(document.querySelector('.main-content') || document.body, {
            childList: true,
            subtree: true
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize page animations
    new PageAnimations();
    
    // Initialize loan calculator
    new LoanCalculator();

    // Initialize table filters
    new TableFilter('receiptLogTable', 'receiptFilter');
    new TableFilter('loanLedgerTable', 'ledgerFilter');

    // Print function
    window.printPromissoryNote = function() {
        const printWindow = window.open('', '', 'height=600,width=800');
        const printContent = document.getElementById('promissoryNoteContent').innerHTML;
        printWindow.document.write('<html><head><title>Promissory Note</title>');
        printWindow.document.write(document.head.innerHTML);
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    };

    // Export to CSV
    window.exportTableToCSV = function(tableId, filename) {
        const table = document.getElementById(tableId);
        let csv = [];
        
        // Get headers
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => th.textContent.trim());
        csv.push(headers.join(','));

        // Get rows
        Array.from(table.querySelectorAll('tbody tr')).forEach(tr => {
            const row = Array.from(tr.querySelectorAll('td'))
                .map(td => `"${td.textContent.trim()}"`);
            csv.push(row.join(','));
        });

        // Create blob and download
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || 'export.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    };
});
