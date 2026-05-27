---
name: deploy-cpanel
description: CPanel deployment checklist for Link BPS Enrekang. Use before deploying to production to ensure all steps are completed in the right order.
disable-model-invocation: true
---

Walk the user through deploying to CPanel, step by step. Confirm each step before proceeding to the next.

## Pre-deploy checklist

Ask the user to confirm:
- [ ] All changes are committed and pushed to master
- [ ] `.env` on the server is up to date (compare with `.env.example` for new keys)
- [ ] Database backup taken (if running migrations)

## Build step (run locally first)

```bash
npm run build
```

Commit the compiled assets in `public/build/` if not gitignored, or transfer them via FTP.

## Server-side steps (via CPanel SSH or File Manager)

1. **Pull latest code**
   ```bash
   git pull origin master
   ```

2. **Install/update PHP dependencies** (if composer.json changed)
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Run migrations**
   ```bash
   php artisan migrate --force
   ```
   Warn the user if any migration drops columns or tables — confirm before proceeding.

4. **Cache everything**
   ```bash
   php artisan optimize
   ```
   This caches config, routes, and views for production performance.

5. **Clear old caches if needed**
   ```bash
   php artisan optimize:clear
   php artisan optimize
   ```

6. **Restart queue workers** (if queue jobs changed)
   ```bash
   php artisan queue:restart
   ```

## CPanel document root

Ensure the CPanel domain/subdomain points to the `public/` directory, not the project root. If not set, instruct the user to update it in CPanel → Domains → Document Root.

## Post-deploy verification

- Visit the production URL and confirm the app loads
- Check `storage/logs/laravel.log` for errors
- Test login and a core feature (link creation)
