<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDOException;

class CreateOpenOverheidDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open-overheid:create-db 
                            {--host=127.0.0.1 : PostgreSQL host}
                            {--port=5432 : PostgreSQL port}
                            {--username=postgres : PostgreSQL username}
                            {--password= : PostgreSQL password}
                            {--database=open_overheid : Database name to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create PostgreSQL database for Open Overheid documents';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $host = $this->option('host');
        $port = $this->option('port');
        $username = $this->option('username');
        $password = $this->option('password') ?: $this->secret('Enter PostgreSQL password:');
        $database = $this->option('database');

        $this->info("Creating PostgreSQL database: {$database}");

        try {
            // Connect to PostgreSQL server (not to a specific database)
            $connection = new \PDO(
                "pgsql:host={$host};port={$port}",
                $username,
                $password
            );
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Check if database already exists
            $stmt = $connection->query(
                'SELECT 1 FROM pg_database WHERE datname = '.$connection->quote($database)
            );
            $exists = $stmt->fetch();

            if ($exists) {
                if (! $this->confirm("Database '{$database}' already exists. Continue anyway?", true)) {
                    $this->info('Aborted.');

                    return self::SUCCESS;
                }
            } else {
                // Create the database
                $connection->exec("CREATE DATABASE {$database}");
                $this->info("✓ Database '{$database}' created successfully!");
            }

            // Connect to the new database to set up extensions
            $dbConnection = new \PDO(
                "pgsql:host={$host};port={$port};dbname={$database}",
                $username,
                $password
            );
            $dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Enable extensions
            try {
                $dbConnection->exec('CREATE EXTENSION IF NOT EXISTS pg_trgm');
                $this->info('✓ Extension pg_trgm enabled');
            } catch (PDOException $e) {
                $this->warn('Could not enable pg_trgm extension: '.$e->getMessage());
            }

            $this->newLine();
            $this->info('Database setup complete!');
            $this->newLine();
            $this->line('Next steps:');
            $this->line('1. Update your .env file with:');
            $this->line('   DB_CONNECTION=pgsql');
            $this->line("   DB_HOST={$host}");
            $this->line("   DB_PORT={$port}");
            $this->line("   DB_DATABASE={$database}");
            $this->line("   DB_USERNAME={$username}");
            $this->line("   DB_PASSWORD={$password}");
            $this->newLine();
            $this->line('2. Run migrations:');
            $this->line('   php artisan migrate');

            return self::SUCCESS;
        } catch (PDOException $e) {
            $this->error('Failed to create database: '.$e->getMessage());
            $this->newLine();
            $this->line('Alternative: Use the SQL script manually:');
            $this->line('   psql -U postgres -f database/setup_postgresql.sql');
            $this->line('   or see database/README_POSTGRESQL.md for more options');

            return self::FAILURE;
        }
    }
}
