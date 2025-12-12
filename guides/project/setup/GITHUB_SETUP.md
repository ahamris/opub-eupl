# GitHub Setup Guide

This document outlines what has been prepared for GitHub and what you need to do before pushing to GitHub.

## ✅ Completed Preparations

### 1. `.gitignore` File
- ✅ Updated with comprehensive Laravel ignore rules
- ✅ Storage directories properly configured (ignores files, keeps structure)
- ✅ Bootstrap cache ignored
- ✅ Environment files ignored (`.env` files)
- ✅ IDE files ignored
- ✅ Build artifacts ignored
- ✅ Database files ignored
- ✅ Package lock files kept (for consistency)

### 2. `LICENSE` File
- ✅ Created MIT License file

### 3. `README.md`
- ✅ Already exists with comprehensive documentation

## ⚠️ Manual Steps Required

### 1. Update `.env.example`
The `.env.example` file exists but needs to be updated with Open Overheid specific variables. Add these to the end of your `.env.example`:

```env
# Open Overheid API Configuration
OPEN_OVERHEID_BASE_URL=https://open.overheid.nl/overheid/openbaarmakingen/api/v0
OPEN_OVERHEID_TIMEOUT=10
OPEN_OVERHEID_SYNC_ENABLED=true
OPEN_OVERHEID_SYNC_BATCH_SIZE=50
OPEN_OVERHEID_SYNC_DAYS_BACK=1
OPEN_OVERHEID_USE_LOCAL_SEARCH=true

# Typesense Configuration (Optional)
TYPESENSE_SYNC_ENABLED=false
TYPESENSE_API_KEY=
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
```

Also update the database connection to use PostgreSQL by default:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=open_overheid
DB_USERNAME=postgres
DB_PASSWORD=
```

### 2. Review Files Before Committing

**Check these sensitive files are NOT committed:**
- ✅ `.env` files (already in .gitignore)
- ✅ `database/database.sqlite` (already in .gitignore)
- ✅ `storage/logs/laravel.log` (already in .gitignore)
- ✅ Any API keys or secrets

**Ensure these ARE committed:**
- ✅ `LICENSE`
- ✅ `README.md`
- ✅ `.env.example` (template only, no secrets)
- ✅ `package-lock.json` (for dependency consistency)
- ✅ `composer.lock` (should be committed)
- ✅ All source code files

**Note about `public/vendor/fontawesome/`:**
This directory exists in your project. If FontAwesome is installed via npm and copied to public, consider:
- Adding `/public/vendor/` to `.gitignore` if it's generated during build
- OR keeping it if it's manually installed and needed for production

### 3. Initialize Git Repository (if not already done)

```bash
git init
git add .
git commit -m "Initial commit: Open Overheid Platform"
```

### 4. Create GitHub Repository

1. Go to GitHub and create a new repository
2. Do NOT initialize with README, .gitignore, or license (we already have these)
3. Follow GitHub's instructions to push:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git branch -M main
git push -u origin main
```

### 5. Recommended: Add GitHub Workflows (Optional)

Consider adding:
- `.github/workflows/tests.yml` - Run tests on push/PR
- `.github/workflows/phpstan.yml` - Static analysis
- `.github/dependabot.yml` - Dependency updates

## 📋 Pre-Push Checklist

Before pushing to GitHub:

- [ ] Verify `.env` is NOT in the repository
- [ ] Verify `.env.example` exists and has all variables (without values)
- [ ] Review `.gitignore` covers all sensitive files
- [ ] Check no API keys or secrets in code
- [ ] Verify `LICENSE` file is present
- [ ] Update `README.md` if needed (remove placeholder repository URL)
- [ ] Review commit history (no sensitive data in commits)
- [ ] Test that fresh clone works: `git clone` → `composer install` → `npm install`

## 🔒 Security Notes

- Never commit `.env` files
- Never commit API keys, passwords, or secrets
- Use GitHub Secrets for CI/CD environment variables
- Review all files before first push
- Consider using `git-secrets` or `truffleHog` to scan for secrets

## 📝 Additional Files You May Want to Add

- `CONTRIBUTING.md` - Contribution guidelines
- `CHANGELOG.md` - Version history
- `.github/ISSUE_TEMPLATE/` - Issue templates
- `.github/PULL_REQUEST_TEMPLATE.md` - PR template
- `SECURITY.md` - Security policy

## 🚀 Next Steps

1. Complete the manual steps above
2. Initialize git and make initial commit
3. Create GitHub repository
4. Push to GitHub
5. Set up branch protection rules (optional)
6. Configure GitHub Actions/CI (optional)

---

**Note**: This file can be deleted after setup is complete, or kept for reference.
