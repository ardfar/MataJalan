# Troubleshooting Guide

## Common Issues

### 1. Application Returns "500 Server Error"
- **Cause**: Code error or misconfiguration.
- **Fix**: Check the logs at `storage/logs/laravel.log`. Ensure `.env` is configured correctly and permissions on `storage/` are set to 775.

### 2. "Vite manifest not found"
- **Cause**: Frontend assets haven't been built.
- **Fix**: Run `npm run build` (for production) or ensure `npm run dev` is running (for local development).

### 3. Database Connection Failed
- **Cause**: Incorrect credentials in `.env` or database service is down.
- **Fix**: Verify `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`. Ensure MySQL/PostgreSQL service is running.

### 4. Styles Not Loading (Tailwind)
- **Cause**: `npm run dev` not running or `app.css` not included correctly.
- **Fix**: Ensure `@vite(['resources/css/app.css', 'resources/js/app.js'])` is in your layout head.

### 5. Permission Denied on `storage/`
- **Cause**: Web server user does not own the directory.
- **Fix**: Run `chmod -R 775 storage` and `chown -R www-data:www-data storage`.

## Debugging Tools
- **Laravel Telescope**: Use (if installed) for deep inspection of requests, exceptions, and database queries.
- **`dd()` / `dump()`**: Use PHP helpers to dump variable contents during development.
- **Log Files**: Always check `storage/logs/laravel.log` first.
