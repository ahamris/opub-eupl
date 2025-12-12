# Quick Setup Guide - PostgreSQL Database

## Fastest Method: Artisan Command

Run this command to create the database automatically:

```bash
php artisan open-overheid:create-db
```

You'll be prompted for your PostgreSQL password. The command will:
- Create the `open_overheid` database
- Enable required extensions
- Show you the `.env` configuration you need

## Manual Method: SQL Script

If you prefer to do it manually:

```bash
psql -U postgres -f database/setup_postgresql.sql
```

## After Database Creation

1. **Update `.env` file:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=open_overheid
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

2. **Run migrations:**
   ```bash
   php artisan migrate
   ```

3. **Verify setup:**
   ```bash
   php artisan tinker
   ```
   Then in tinker:
   ```php
   \App\Models\OpenOverheidDocument::count();
   ```

## That's it!

Your database is ready. You can now:
- Sync documents: `php artisan open-overheid:sync`
- Search via API: `GET /open-overheid/search`

