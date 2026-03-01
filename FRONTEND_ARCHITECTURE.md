# Coop eHub Loan Management System - Frontend Architecture

## Overview

This is a comprehensive Bootstrap 5-based frontend architecture for the Coop eHub Cooperative Loan Management System. The system features a professional sidebar navigation, responsive design, and specialized components for loan management.

## Features Implemented

### 1. **UI/UX Design**
- **Clean Professional Sidebar Navigation**: Blue gradient sidebar (#0d6efd) with hover effects
- **Bootstrap 5 Components**: Cards, tables, modals, badges, buttons, forms
- **Coop eHub Branding**: Custom color scheme with deep blue primary and soft gray backgrounds
- **Responsive Design**: Mobile-first approach with media queries for all screen sizes

### 2. **Key Components**

#### Dashboard
- **Path**: `resources/views/dashboard.blade.php`
- Summary cards showing:
  - Total Members
  - Total Loans Outstanding
  - Total Collections
  - Overdue Accounts
- Recent transactions with quick links
- System status indicator

#### Members Management
- **Create Member**: `resources/views/members/create.blade.php`
  - Form with personal info, account details, emergency contacts
  - Input validation and error handling
  
- **Edit Member**: `resources/views/members/edit.blade.php`
  - Update member information
  - Account status management
  - Delete member functionality
  
- **Member Directory**: `resources/views/members/index.blade.php`
  - Searchable table of all members
  - Member status badges (Active/Client/Inactive)
  - Quick action buttons
  
- **Member Profile**: `resources/views/members/show.blade.php`
  - Detailed member information
  - Active loans display
  - Payment history

#### Receipt Log
- **Path**: `resources/views/receipt-log/index.blade.php`
- **Features**:
  - Advanced filtering by date and transaction type
  - Searchable table showing:
    - Receipt Number
    - Member Entity
    - Transaction Type
    - Amount (with color-coded indicators)
    - Process Date
    - Status badges
  - Export to CSV functionality
  - Transaction detail modal

#### Loan Portfolio / Statement of Account (SOA)
- **Path**: `resources/views/loan-portfolio/index.blade.php`
- **Features**:
  - Member selection dropdown
  - Summary cards for:
    - Member Status (Active/Client/Inactive badges)
    - Total Principal Balance
    - Interest Accrued
  - Chronological loan ledger showing:
    - Transaction Date
    - Description (Payment, Interest Charge, Disbursement)
    - Principal Paid / Interest Paid columns
    - Running Balance
    - Reference codes
  - Active loans cards with detailed information
  - SOA generation modal
  - CSV export

#### Loan Summary Calculator Component
- **Path**: `resources/views/components/loan-summary.blade.php`
- **Features**:
  - Real-time calculation as user types
  - Inputs: Loan Amount, Interest Rate (% per annum), Loan Term (Years)
  - Outputs:
    - Monthly Installment
    - Total Repayment Amount
  - Uses compound interest formula
  - Responsive layout

#### Promissory Note
- **Path**: `resources/views/promissory-note/template.blade.php`
- **Features**:
  - Professional document layout
  - Dynamic data fields:
    - Borrower name & date
    - Promissory note number
    - Loan details (amount, interest rate, term)
    - Payment schedule table
    - Signature blocks (Borrower, Witness, Authorized Officer)
  - Print-optimized CSS (A4/Letter page size)
  - Page break prevention for signature section
  - Print stylesheet that hides navigation

#### Audit Ledger
- **Path**: `resources/views/audit-ledger/index.blade.php`
- **Features**:
  - Complete activity log with:
    - Timestamp
    - User name
    - Activity type (color-coded badges)
    - Module/Section
    - Reference ID
    - Activity details
    - IP Address
  - Filtering capabilities
  - Activity statistics
  - Most active users report
  - CSV export

#### Main Layout
- **Path**: `resources/views/layouts/app.blade.php`
- **Features**:
  - Sidebar navigation with active state indicators
  - Top header with page title and user info
  - Alert messages (success, error)
  - Responsive grid layout

### 3. **Loan Summary Calculator**

**JavaScript Implementation** (`resources/js/app.js`):

```javascript
// Real-time calculation
const monthlyRate = (interestRate / 100) / 12;
const numPayments = loanTerm * 12;

// Compound interest formula
Monthly Payment = Principal * [r(1+r)^n] / [(1+r)^n - 1]
Total Repayment = Monthly Payment * Number of Payments
```

**Usage**:
1. Enter Loan Amount
2. Enter Annual Interest Rate (%)
3. Enter Loan Term (Years)
4. Calculator automatically computes Monthly Installment and Total Repayment

### 4. **Member Status Badges**

Color-coded visual indicators:
- ✅ **Active** (Green - #198754): Full account access
- ⚠️ **Client** (Yellow - #ffc107): Client status
- ❌ **Inactive** (Red - #dc3545): Account disabled

### 5. **Print Functionality**

**Promissory Note Print Features**:
- Print-optimized layout with A4/Letter page size
- Hides navigation and buttons
- Maintains professional formatting
- Page break prevention for signatures
- Automatic page margins (1cm)

**Usage**:
```javascript
// Call window.printPromissoryNote() to print
// Or use browser's Print dialog (Ctrl+P)
```

### 6. **Data Export**

**CSV Export Function**:
```javascript
exportTableToCSV('tableId', 'filename.csv')
```

**Supported Tables**:
- Receipt Log
- Loan Ledger
- Audit Log

### 7. **Table Filtering**

**Real-time search functionality**:
```javascript
new TableFilter('receiptLogTable', 'receiptFilter')
```

Filters work across all table columns for:
- Member names
- Transaction amounts
- Dates
- Description

## Color Scheme

### Coop eHub Branding Colors

| Color | Hex Code | Usage |
|-------|----------|-------|
| Primary Blue | #0d6efd | Sidebar, headers, primary buttons |
| Primary Dark | #0b5ed7 | Button hover states |
| Success Green | #198754 | Active status badges |
| Warning Yellow | #ffc107 | Client status badges |
| Danger Red | #dc3545 | Inactive status, delete actions |
| Info Cyan | #0dcaf0 | Information badges |
| Light Gray | #f8f9fa | Card backgrounds |
| Dark Gray | #e9ecef | Section backgrounds |
| Border | #dee2e6 | Table borders, dividers |

## File Structure

```
resources/
├── css/
│   └── app.css                      # Main styles with Bootstrap import
├── js/
│   └── app.js                       # JavaScript components & utilities
└── views/
    ├── layouts/
    │   └── app.blade.php            # Main layout template
    ├── dashboard.blade.php          # Dashboard home page
    ├── members/
    │   ├── index.blade.php          # Member directory
    │   ├── create.blade.php         # New member form
    │   ├── edit.blade.php           # Edit member form
    │   └── show.blade.php           # Member profile
    ├── receipt-log/
    │   └── index.blade.php          # Receipt log & filtering
    ├── loan-portfolio/
    │   └── index.blade.php          # Loan SOA & ledger
    ├── audit-ledger/
    │   └── index.blade.php          # Audit log
    ├── promissory-note/
    │   ├── template.blade.php       # Note template
    │   └── show.blade.php           # Print view
    └── components/
        └── loan-summary.blade.php   # Calculator component
```

## Installation & Setup

### 1. Install Dependencies

```bash
npm install
```

### 2. Update Vite Config

The project uses Bootstrap 5 instead of Tailwind. Configuration is ready in `vite.config.js`.

### 3. Compile Assets

```bash
npm run dev    # Development with hot reload
npm run build  # Production build
```

### 4. Create Routes

Add the following routes to `routes/web.php`:

```php
// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Members
    Route::resource('members', MemberController::class);
    
    // Receipt Log
    Route::resource('receipt-log', ReceiptLogController::class)->only(['index', 'show']);
    
    // Loan Portfolio
    Route::resource('loan-portfolio', LoanPortfolioController::class)->only(['index', 'show']);
    
    // Audit Ledger
    Route::resource('audit-ledger', AuditLedgerController::class)->only(['index']);
});
```

## Data Integration

### Dashboard Controller

```php
public function index()
{
    return view('dashboard', [
        'totalMembers' => Member::count(),
        'totalLoans' => Loan::sum('principal_amount'),
        'totalCollections' => Receipt::where('type', 'payment')->sum('amount'),
        'overdueAccounts' => Loan::where('due_date', '<', now())->count(),
    ]);
}
```

### Receipt Log Controller

```php
public function index()
{
    return view('receipt-log.index', [
        'receipts' => Receipt::latest()->paginate(15),
    ]);
}
```

### Members Controller

```php
public function index()
{
    return view('members.index', [
        'members' => Member::paginate(20),
    ]);
}
```

### Loan Portfolio Controller

```php
public function index()
{
    return view('loan-portfolio.index', [
        'loans' => Loan::with('member')->get(),
        'member' => Member::find(request('member_id')),
    ]);
}
```

## Responsive Design Breakpoints

- **Mobile**: < 768px (full-width sidebar)
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px (sticky sidebar)

## Print Styles

Print-specific CSS automatically:
- Hides navigation and buttons
- Removes margins for optimal page usage
- Ensures page breaks only occur at safe points
- Maintains professional formatting on A4/Letter pages

## JavaScript Utilities

### Loan Calculator
```javascript
new LoanCalculator(); // Auto-initializes on page load
```

### Table Filtering
```javascript
new TableFilter('receiptLogTable', 'receiptFilter');
```

### Bootstrap Modal Support
All modals use Bootstrap 5 native functionality with data attributes.

### Export Function
```javascript
exportTableToCSV('receiptsTable', 'receipts.csv');
```

### Print Function
```javascript
printPromissoryNote();
```

## Browser Support

- Chrome/Edge 88+
- Firefox 87+
- Safari 14+
- Mobile browsers (iOS Safari 14+, Chrome Mobile)

## Accessibility Features

- Semantic HTML5 structure
- ARIA labels on form inputs
- Keyboard navigation support
- High contrast color combinations
- Readable font sizes (base 0.95rem)
- Proper heading hierarchy

## Performance Optimizations

- Bootstrap CSS imported via SCSS for tree-shaking
- Minimal custom CSS (only Coop eHub branding)
- Lazy loading for images
- Font Awesome icons (minimal set)
- Optimized table rendering for large datasets

## Future Enhancements

1. **Dark Mode Support**: Add dark mode toggle using CSS variables
2. **Charts & Analytics**: Add Chart.js integration for visual reports
3. **Email Receipts**: Send transaction receipts via email
4. **SMS Notifications**: Integration for member notifications
5. **Bulk Import**: Excel/CSV import for members and transactions
6. **Mobile App**: React Native companion app

## Troubleshooting

### Styles Not Loading
- Run `npm run build` to compile assets
- Clear browser cache
- Check if `@vite` directive is in layout

### Loan Calculator Not Working
- Ensure JavaScript is enabled
- Check browser console for errors
- Verify `resources/js/app.js` is included

### Print Not Optimal
- Adjust print margins in browser settings
- Ensure "Print backgrounds" is enabled
- Test with different browsers

## Support & Documentation

For questions or issues:
1. Check the inline code comments
2. Review Bootstrap 5 documentation: https://getbootstrap.com/docs/5.3/
3. Refer to Laravel Blade documentation: https://laravel.com/docs/blade

---

**Version**: 1.0.0  
**Last Updated**: March 1, 2026  
**Maintained by**: Coop eHub Development Team
