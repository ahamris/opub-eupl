# Server & resilience improvements

Based on production log analysis (Laravel logs Jan–Feb 2026), this guide summarizes **code changes** already applied and **server/infra** actions to run on the host.

---

## Code changes (already applied)

### 1. Mail config from DB no longer breaks bootstrap
- **File:** `app/Providers/AppServiceProvider.php`
- **Change:** `configureMailFromDatabase()` is fully wrapped in `try/catch`. If the DB is unreachable (timeout) or the `settings` table is missing, the app still boots and uses `.env` / `config/mail.php` for mail.
- **Effect:** PostgreSQL connection timeouts no longer take down every request.

### 2. Cache failover when Redis is down
- **File:** `config/cache.php`
- **Change:** In production, the default cache store is `redis_then_database`: it uses Redis first, then falls back to the database store if Redis fails (e.g. MISCONF / disk full).
- **Override:** Set `CACHE_STORE=redis` in `.env` if you want cache to fail hard on Redis errors instead of falling back.

---

## Server / infrastructure checklist

### Redis (MISCONF – “unable to persist to disk”)

1. **Check disk space**  
   `df -h` on the Redis server; ensure the volume where Redis writes (e.g. `/var/lib/redis`) has free space.

2. **Check Redis logs**  
   e.g. `/var/log/redis/redis-server.log` (path depends on install). Look for the exact RDB error (e.g. “Can’t save in background: fork()”, “No space left”, permission errors).

3. **Fix cause**  
   - Free disk or move Redis data to a larger volume.  
   - Fix ownership/permissions on Redis data dir and RDB file.  
   - If `fork()` fails: consider `vm.overcommit_memory=1` (see Redis docs).

4. **Temporary workaround (accepts no persistence)**  
   On the Redis server:  
   `redis-cli CONFIG SET stop-writes-on-bgsave-error no`  
   Use only to unblock while fixing disk/permissions; then fix and restart Redis.

5. **Session when Redis is flaky**  
   Laravel has no built-in session failover. If Redis is down, sessions fail. You can temporarily switch to DB sessions:  
   `SESSION_DRIVER=database` in `.env` (and run `php artisan session:table` + migrate if not already). Revert to `redis` when Redis is healthy.

---

### PostgreSQL (connection timeout to `45.140.140.11:5432`)

1. **Reachability**  
   From the app server:  
   `nc -zv 45.140.140.11 5432` or `telnet 45.140.140.11 5432`.  
   If it times out, the DB host is down, firewall/security group blocks 5432, or network issue.

2. **Correct config**  
   Ensure production `.env` has the right `DB_HOST`, `DB_PORT`, and credentials for the actual DB server (not an old IP).

3. **DB server**  
   Confirm PostgreSQL is running and listening on 5432; check its logs for connection/load issues.

---

### Deploy / PHP parse error (RequireOpubApiKey.php)

- The “syntax error, unexpected token private” at line 193 was from an **older version** of the middleware on production. The repo version is valid.
- **Action:** Deploy the current codebase to production and clear caches (e.g. `php artisan config:clear` and opcache if used). Ensure the deployed `app/Http/Middleware/RequireOpubApiKey.php` matches the repo.

---

## Optional: monitoring

- **Disk:** Alert when free space on Redis/DB/app volumes drops below a threshold.
- **Redis:** Monitor `used_memory`, RDB save success/failure, and `connected_clients`.
- **PostgreSQL:** Monitor connections and slow queries; ensure the app server can reach the DB port.

---

## Summary

| Issue              | Code change                         | Server action                                      |
|--------------------|-------------------------------------|----------------------------------------------------|
| DB timeout at boot | Mail-from-DB wrapped in try/catch   | Fix DB reachability and config                    |
| Redis MISCONF      | Cache failover Redis → database     | Fix Redis disk/permissions; optional SESSION_DRIVER=database |
| Parse error        | Repo is fixed                       | Deploy current code and clear caches              |
