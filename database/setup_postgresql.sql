-- Create PostgreSQL database for Open Overheid documents
-- Run this script as a PostgreSQL superuser (usually 'postgres')

-- Create the database
CREATE DATABASE open_overheid
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8'
    TEMPLATE = template0;

-- Connect to the new database
\c open_overheid

-- Enable required extensions for full-text search
CREATE EXTENSION IF NOT EXISTS pg_trgm;  -- For trigram similarity (optional, for fuzzy search)
-- Note: The 'dutch' language for full-text search should be available by default
-- If not, you may need to install it via: CREATE EXTENSION IF NOT EXISTS unaccent;

-- Grant permissions (adjust username as needed)
-- GRANT ALL PRIVILEGES ON DATABASE open_overheid TO your_username;

COMMENT ON DATABASE open_overheid IS 'Database for storing Open Overheid (openbaarmakingen) documents with full-text search capabilities';

