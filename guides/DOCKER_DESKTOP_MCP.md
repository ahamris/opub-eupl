# Docker Desktop MCP Integration Guide

This guide explains how to integrate Docker Desktop's MCP Toolkit with Typesense for the Open Overheid application.

## Prerequisites

- Docker Desktop version 4.42 or later
- Typesense running in Docker (already configured)

## Current Setup

Typesense is currently running via Docker Compose:

```bash
docker-compose up -d typesense
```

The Typesense container is accessible at:
- Host: `localhost`
- Port: `8108`
- Protocol: `http`
- API Key: See `.env` file (`TYPESENSE_API_KEY`)

## Enabling Docker Desktop MCP Toolkit

1. **Open Docker Desktop**
   - Launch Docker Desktop application

2. **Enable MCP Toolkit**
   - Navigate to **Settings** (gear icon)
   - Select **Beta features** from the left sidebar
   - Check the box for **Enable Docker MCP Toolkit**
   - Click **Apply & Restart** if prompted

3. **Access MCP Toolkit**
   - After restart, you'll see an **MCP Toolkit** tab in Docker Desktop
   - This provides a UI for managing MCP servers

## Using Typesense with Docker Desktop MCP Toolkit

### Option 1: Use Existing Docker Compose Setup (Current)

The Typesense container is already running via `docker-compose.yml`. This is the recommended approach for development:

```bash
# Start Typesense
docker-compose up -d typesense

# Check status
docker ps | grep typesense

# View logs
docker logs openoverheid-typesense

# Stop Typesense
docker-compose down typesense
```

### Option 2: Install Typesense via Docker Desktop MCP Toolkit

If you prefer to manage Typesense through Docker Desktop's MCP Toolkit UI:

1. Open **MCP Toolkit** tab in Docker Desktop
2. Go to **Catalog** tab
3. Search for "Typesense"
4. Click **Add** to install
5. Configure:
   - Set `TYPESENSE_API_KEY` environment variable
   - Map port `8108` to host
6. Start the server from the **Servers** tab

## Connecting to Typesense from Laravel

The application is already configured to connect to Typesense. Configuration is in `.env`:

```env
TYPESENSE_SYNC_ENABLED=true
TYPESENSE_API_KEY=your_api_key_here
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
```

## Syncing Documents to Typesense

After Typesense is running, sync documents from PostgreSQL:

```bash
php artisan typesense:sync
```

This command will:
- Create the collection schema if it doesn't exist
- Sync all documents from the database to Typesense
- Update the `typesense_synced_at` timestamp

## Verifying the Setup

1. **Check Typesense health:**
   ```bash
   curl http://localhost:8108/health
   ```
   Should return: `{"ok":true}`

2. **Check connection from Laravel:**
   ```bash
   php artisan tinker
   ```
   ```php
   $client = new \Typesense\Client([
       'api_key' => env('TYPESENSE_API_KEY'),
       'nodes' => [[
           'host' => 'localhost',
           'port' => 8108,
           'protocol' => 'http',
       ]],
   ]);
   $client->getHealth()->retrieve();
   ```

3. **Test search:**
   - Visit `/zoek` page
   - Use the live search feature
   - Search should now use Typesense instead of PostgreSQL fallback

## Docker Desktop MCP Gateway

Docker Desktop's MCP Toolkit includes an MCP Gateway that acts as a centralized proxy. 

### Connection Status

✅ **Cursor is now connected to Docker Desktop MCP!**

The connection was established using:
```bash
docker mcp client connect cursor --global
```

This provides access to Docker MCP tools within Cursor:
- `code-mode` - Create JavaScript-enabled tools combining multiple MCP servers
- `mcp-add` - Add new MCP servers to the session
- `mcp-config-set` - Configure MCP server settings
- `mcp-exec` - Execute MCP server tools
- `mcp-find` - Find MCP servers in the catalog
- `mcp-remove` - Remove MCP servers

### Available Docker MCP Catalog

The Docker MCP catalog contains 272+ pre-configured MCP servers, including:
- Database servers (SQLite, PostgreSQL, MongoDB, etc.)
- Cloud services (AWS, Azure, Google Cloud)
- Development tools
- And many more

To browse available servers:
```bash
docker mcp catalog show docker-mcp
```

To search for specific servers (like database-related):
```bash
docker mcp catalog show docker-mcp | grep -i "database"
```

Example database-related servers available:
- `database-server` - Comprehensive database server (PostgreSQL, MySQL, SQLite) with natural language SQL queries
- `sqlite` - SQLite database interaction and business intelligence
- `cockroachdb` - CockroachDB management and querying

**Note:** The `docker mcp tools call` command requires JSON input via stdin. For interactive usage, it's easier to:
1. Use the Docker Desktop UI (MCP Toolkit tab)
2. Or use `docker mcp catalog show` to browse servers
3. Or add servers directly if you know their names

Note: Typesense is managed via Docker Compose (recommended for development). The Docker Desktop MCP connection allows you to manage and interact with Docker containers and services directly from Cursor.

## Troubleshooting

### Typesense container not starting
- Check Docker Desktop is running
- Verify port 8108 is not in use: `lsof -i :8108`
- Check container logs: `docker logs openoverheid-typesense`

### Connection errors
- Verify `TYPESENSE_API_KEY` in `.env` matches the container's API key
- Check firewall settings allow localhost connections
- Ensure the container is healthy: `docker ps | grep typesense`

### Sync issues
- Verify Typesense is accessible: `curl http://localhost:8108/health`
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Run sync with verbose output: `php artisan typesense:sync -v`

