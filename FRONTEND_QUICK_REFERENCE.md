# Frontend Architecture - Quick Reference Guide

## 🎨 Color Palette

```css
:root {
    --ss-primary: #0d6efd;           /* Deep Blue */
    --ss-primary-dark: #0b5ed7;      /* Dark Blue */
    --ss-secondary: #6c757d;         /* Gray */
    --ss-success: #198754;           /* Green - Active */
    --ss-danger: #dc3545;            /* Red - Inactive */
    --ss-warning: #ffc107;           /* Yellow - Client */
    --ss-info: #0dcaf0;              /* Cyan */
    --ss-light: #f8f9fa;             /* Light Gray */
    --ss-dark-gray: #e9ecef;         /* Dark Gray */
    --ss-border-color: #dee2e6;      /* Border */
}
```

## 📋 Common Classes

### Summary Cards
```html
<!-- Success card -->
<div class="summary-card success">
    <div class="summary-card-title">Title</div>
    <div class="summary-card-value">₱5,000.00</div>
</div>

<!-- Warning card -->
<div class="summary-card warning">
    <div class="summary-card-title">Title</div>
    <div class="summary-card-value">5</div>
</div>
```

### Status Badges
```html
<span class="badge status-active">Active</span>
<span class="badge status-client">Client</span>
<span class="badge status-inactive">Inactive</span>
```

### Amount Text
```html
<span class="amount-positive">+ ₱5,000.00</span>  <!-- Green -->
<span class="amount-negative">- ₱5,000.00</span>  <!-- Red -->
```

### Tables
```html
<table class="table table-hover transaction-table">
    <thead>
        <tr>
            <th>Column</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data</td>
        </tr>
    </tbody>
</table>
```

## 🔧 JavaScript Functions

### Loan Calculator
```javascript
// Auto-initializes when DOM loads
new LoanCalculator();

// Returns formatted currency
formatCurrency(2500.50) // Returns "₱2,500.50"
```

### Table Filter
```javascript
// Creates searchable table
new TableFilter('receiptLogTable', 'receiptFilter');

// Filters rows in real-time as user types
```

### Export to CSV
```javascript
exportTableToCSV('tableId', 'filename.csv');

// Exports all visible rows to CSV file
```

### Print Promissory Note
```javascript
printPromissoryNote();

// Prints the promissory note with A4 format
```

## 📱 Responsive Classes

### Hidden/Visible by Screen Size
```html
<!-- Hidden on mobile, visible on desktop -->
<div class="d-none d-md-block"></div>

<!-- Full width on mobile, normal on desktop -->
<div class="col-12 col-md-6"></div>
```

### Grid Sizes
```html
<!-- 12-column grid -->
<div class="row">
    <div class="col-md-3">25%</div>
    <div class="col-md-6">50%</div>
    <div class="col-md-3">25%</div>
</div>
```

## 🎯 Common Patterns

### Card Layout
```blade
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Title</h5>
    </div>
    <div class="card-body">
        Content goes here
    </div>
</div>
```

### Form Group
```blade
<div class="mb-3">
    <label class="form-label">Label *</label>
    <input type="text" class="form-control" required>
    @error('field')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

### Button Group
```html
<div class="btn-group" role="group">
    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
    <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
</div>
```

### Modal
```blade
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Content
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary">Action</button>
            </div>
        </div>
    </div>
</div>
```

## 🔗 Navigation Structure

```
Sidebar Active States:
- Dashboard (1st link)
  └── Home view
- Members
  └── Directory, Create, Edit, View
- Receipt Log
  └── Filtered table view
- Loan Portfolio
  └── SOA, Ledger view
- Audit Ledger
  └── Activity log
```

## 📊 Data Binding Examples

### With Loop
```blade
@forelse($members as $member)
    <tr>
        <td>{{ $member->name }}</td>
        <td><span class="badge status-active">{{ $member->status }}</span></td>
    </tr>
@empty
    <tr><td colspan="2">No members found</td></tr>
@endforelse
```

### Conditional Rendering
```blade
@if($member->status === 'active')
    <span class="badge status-active">Active</span>
@elseif($member->status === 'client')
    <span class="badge status-client">Client</span>
@else
    <span class="badge status-inactive">Inactive</span>
@endif
```

## 🔐 Protected Routes Pattern

```blade
<!-- Active link indicator -->
<a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" 
   href="{{ route('dashboard') }}">Dashboard</a>

<!-- CSRF Protection -->
@csrf

<!-- Method Spoofing -->
@method('DELETE')
```

## 📝 Form Validation Display

```blade
<!-- Show validation errors -->
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!-- Show success message -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

## 🎬 Animation Classes

```css
.transition-all       /* Smooth transitions */
.shadow-sm           /* Subtle shadow */
.shadow-md           /* Medium shadow */
.opacity-50          /* 50% opacity */
```

## 🖨️ Print CSS Classes

```html
<!-- Hide on print -->
<button class="btn no-print">Print</button>

<!-- Print-optimized element -->
<div class="promissory-note">Content</div>
```

## 🧮 Input Types & Validation

```html
<!-- Currency/Money -->
<input type="number" step="0.01" min="0" placeholder="0.00">

<!-- Date -->
<input type="date">

<!-- Phone -->
<input type="tel" placeholder="(+63) 9XX-XXX-XXXX">

<!-- Email -->
<input type="email" required>
```

## 📦 Component Import Pattern

```blade
{{-- Include loan calculator --}}
@include('components.loan-summary')

{{-- Include promissory note --}}
@include('promissory-note.template')
```

## 🔑 Key Files for Customization

| File | Purpose |
|------|---------|
| `resources/css/app.css` | All styling & branding |
| `resources/js/app.js` | JavaScript components |
| `resources/views/layouts/app.blade.php` | Main layout template |
| `resources/views/components/` | Reusable components |

## 💡 Best Practices

1. **Always use Bootstrap utilities** for spacing (m, p, g, etc.)
2. **Keep custom CSS minimal** - leverage Bootstrap classes
3. **Use semantic HTML** for accessibility
4. **Test responsive design** at multiple breakpoints
5. **Color-code by status** for visual scanning
6. **Use icons consistently** for visual cues
7. **Include loading states** for async operations
8. **Validate on client & server** for security

## 📞 Quick Links

- **Bootstrap Docs**: https://getbootstrap.com/docs/5.3/
- **Font Awesome Icons**: https://fontawesome.com/icons
- **Laravel Blade**: https://laravel.com/docs/blade
- **Form Validation**: https://laravel.com/docs/validation

---

**Last Updated**: March 1, 2026
