# PostgreSQL Database Setup for Open Overheid

This guide will help you set up the PostgreSQL database for storing Open Overheid documents.

## Prerequisites

- PostgreSQL installed and running locally
- PostgreSQL superuser access (usually `postgres` user)
- `psql` command-line tool or pgAdmin

## Option 1: Using psql Command Line

1. Open a terminal/command prompt

2. Connect to PostgreSQL as superuser:
   ```bash
   psql -U postgres
   ```
   (You may be prompted for the postgres user password)

3. Run the setup script:
   ```bash
   psql -U postgres -f database/setup_postgresql.sql
   ```

   Or manually:
   ```sql
   CREATE DATABASE open_overheid;
   ```

4. Verify the database was created:
   ```sql
   \l
   ```
   You should see `open_overheid` in the list.

## Option 2: Using pgAdmin

1. Open pgAdmin
2. Connect to your PostgreSQL server
3. Right-click on "Databases" → "Create" → "Database"
4. Set the database name to: `open_overheid`
5. Set the owner to: `postgres` (or your preferred user)
6. Click "Save"

## Option 3: Using Laravel Artisan (after database exists)

Once the database is created, you can use Laravel to set it up:

1. Update your `.env` file with PostgreSQL credentials:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=open_overheid
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

## Verify Setup

After creating the database and running migrations, verify the setup:

```bash
php artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
// Should return a PDO connection without errors

\App\Models\OpenOverheidDocument::count();
// Should return 0 (no documents yet)
```

## Full-Text Search Language

The migration uses the 'dutch' language for full-text search. If you encounter errors about the language not being available, you may need to:

1. Install the Dutch language pack for PostgreSQL (varies by OS)
2. Or modify the migration to use 'english' instead of 'dutch'

## Troubleshooting

### Connection Refused
- Ensure PostgreSQL service is running
- Check that the host and port are correct (default: 127.0.0.1:5432)

### Authentication Failed
- Verify your username and password in `.env`
- Check PostgreSQL's `pg_hba.conf` file for authentication settings

### Permission Denied
- Ensure your database user has CREATE privileges
- You may need to run as the `postgres` superuser

### Language Not Found
- The 'dutch' language should be available by default
- If not, you can modify the migration to use 'english' or install the Dutch language pack

