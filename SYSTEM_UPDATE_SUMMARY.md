# System Update Summary

## Updated and fixed

- **Loan payment tracking:** payments now apply to the oldest due installment, interest first. Partial payments stay attached to that installment; unpaid interest carries forward.
- **Extra payments and payoff:** money above due installments reduces principal; future installments are recalculated. Early payoff clears future, unearned installments.
- **Payment records and screen:** installment allocations are recorded, and the portfolio shows installment due, arrears, and the accepted payment limit.
- **Loan request preview:** numeric amounts are returned to the browser so thousands are not misread when formatted.
- **New Request modal:** removed the duplicate Bootstrap modal-opening handler that could leave the screen blocked.

## Not fixed / needs attention

- **Cancel loan request:** the attempted cancellation changes were undone at the user's request. The database status support still needs a proper fix.
- **Database recovery:** the configured `laravel` database currently has no member, loan, request, or transaction records. No complete backup was found; an older `coop_db` is only a partial, outdated copy. No restore was performed.

## Verification

- PHP syntax checks and Blade compilation passed.
- The Laravel test suite reported 7 passing tests (22 assertions). Note: cached Laravel configuration may have caused test database isolation to fail, so these results do not verify the real application database or constitute a safe repeatable test setup.

> Back up the database and configure a dedicated test database before running tests or migrations again.
