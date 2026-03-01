# Loan Type Feature Documentation

## Overview
The Loan Type feature has been added to the system to categorize and better organize loans. This allows members to specify what type of loan they're requesting and helps the organization track and analyze different loan categories.

## Available Loan Types

1. **Personal** - For personal and family expenses
2. **Business** - For business operations and expansion
3. **Emergency** - For urgent and unexpected situations
4. **Education** - For educational purposes and training
5. **Medical** - For medical and healthcare expenses
6. **Home** - For home improvement and construction
7. **Agricultural** - For agricultural and farming activities
8. **Other** - Other loan purposes

## Database Schema

### Added Columns

#### `loans` table
- Column: `loan_type`
- Type: ENUM('personal', 'business', 'emergency', 'education', 'medical', 'home', 'agricultural', 'other')
- Default: 'personal'
- Position: After `loan_number`

#### `loan_requests` table
- Column: `loan_type`
- Type: ENUM('personal', 'business', 'emergency', 'education', 'medical', 'home', 'agricultural', 'other')
- Default: 'personal'
- Position: After `requested_amount`

## Migration Files

Two migration files were created to implement this feature:

1. `database/migrations/2026_03_01_000000_add_loan_type_to_loans_table.php`
2. `database/migrations/2026_03_01_000001_add_loan_type_to_loan_requests_table.php`

To apply these migrations, run:
```bash
php artisan migrate
```

## Models & Enums

### Enum Class
- **Location**: `app/Enums/LoanType.php`
- **Features**:
  - `label()` - Returns human-readable label
  - `description()` - Returns detailed description
  - `options()` - Returns all options as key-value array

### Helper Functions
- **Location**: `app/Helpers/LoanTypeHelper.php`
- **Available Functions**:
  - `getLoanTypes()` - Returns all loan types as array
  - `getLoanTypeLabel($type)` - Get label for a loan type
  - `getLoanTypeDescription($type)` - Get description for a loan type
  - `getLoanTypeColor($type)` - Get color code for UI display
  - `getLoanTypeIcon($type)` - Get Font Awesome icon class

## Model Updates

### Loan Model
- Added `loan_type` to `$fillable` array
- Passed through when creating loans from approved loan requests

### LoanRequest Model
- Added `loan_type` to `$fillable` array
- Updated `approve()` method to pass `loan_type` to the created Loan

## User Interface Updates

### Member Dashboard
- **Loan Request Form**: Added loan type dropdown in the "Request New Loan" modal
  - Located after the loan amount field
  - Required field
  - All 8 loan type options available

### Active Loans Display
- Loan type now displayed with a badge next to the loan number
- Color-coded for easy visual identification

### Admin Dashboard
- Loan type information visible in loan listings

## Usage Examples

### In Controllers
```php
// Create a loan with type
$loan = Loan::create([
    'member_id' => $memberId,
    'loan_type' => 'personal', // or use enum: LoanType::PERSONAL->value
    'principal_amount' => 50000,
    // ... other fields
]);

// Get loan type label
$label = getLoanTypeLabel($loan->loan_type); // Returns "Personal"
```

### In Blade Templates
```blade
<!-- Display loan type with icon and color -->
<span style="background: {{ getLoanTypeColor($loan->loan_type) }}; padding: 0.25rem 0.5rem; border-radius: 15px;">
    {{ getLoanTypeLabel($loan->loan_type) }}
</span>

<!-- Loop through all loan types -->
@foreach(getLoanTypes() as $value => $label)
    <option value="{{ $value }}">{{ $label }}</option>
@endforeach
```

### In Forms
```blade
<select name="loan_type" required>
    <option value="">Select loan type...</option>
    @foreach(getLoanTypes() as $value => $label)
        <option value="{{ $value }}" @selected(old('loan_type') === $value)>
            {{ $label }}
        </option>
    @endforeach
</select>
```

## Benefits

1. **Better Organization** - Loans are categorized for easy filtering and analysis
2. **Reporting** - Can generate reports by loan type
3. **Policy Management** - Different interest rates or terms can be applied to different types
4. **Risk Analysis** - Track performance of different loan categories
5. **Member Experience** - Clear categorization helps members understand different loan options

## Future Enhancements

Potential features to implement:
- Loan type-specific interest rates
- Loan type-specific terms and conditions
- Loan type filtering in admin dashboard
- Reports and analytics by loan type
- Default interest rates per loan type

## Notes

- All existing loans default to 'personal' type
- The loan type cannot be changed after loan creation
- Loan request loan type is copied to the created loan upon approval
- Helper functions are autoloaded via composer.json
