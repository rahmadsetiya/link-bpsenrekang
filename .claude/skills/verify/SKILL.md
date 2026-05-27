---
name: verify
description: Run Pest tests and Laravel Pint lint check to validate recent changes. Use after making code changes to confirm nothing is broken.
---

Run the following steps in order to verify recent changes:

1. **Lint check** — run Pint in dry-run mode to catch style issues:
   ```
   ./vendor/bin/pint --test
   ```
   If it reports files to fix, run `./vendor/bin/pint` to auto-fix them, then show the user which files were changed.

2. **Full test suite** — run all Pest tests:
   ```
   php artisan test
   ```

3. **Report results** — summarize:
   - Pass/fail status
   - Any test failures with the error message and file:line
   - Any Pint fixes applied

If tests fail, show the failure output and suggest what to investigate. Do not attempt to fix test failures automatically unless the user asks.
