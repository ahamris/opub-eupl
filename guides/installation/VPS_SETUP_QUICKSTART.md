# VPS Setup - Quick Start Guide

Get your VPS ready for development in 2 minutes!

## 🚀 Quick Setup

### Option 1: Automatic (Recommended)

```bash
# Download and run
curl -fsSL https://raw.githubusercontent.com/YOUR_USERNAME/YOUR_REPO/main/vps-setup.sh | sudo bash -s -- \
  --github-token YOUR_TOKEN \
  --github-username YOUR_USERNAME \
  --github-email YOUR_EMAIL
```

### Option 2: Manual Download

```bash
# 1. Download script
wget https://raw.githubusercontent.com/YOUR_USERNAME/YOUR_REPO/main/vps-setup.sh

# 2. Make executable
chmod +x vps-setup.sh

# 3. Run with your GitHub credentials
sudo ./vps-setup.sh \
  --github-username your-username \
  --github-email your-email@example.com
```

## 📋 What You Need

- **GitHub Username**: Your GitHub username
- **GitHub Email**: Your GitHub email address
- **GitHub Token** (optional): For automatic key upload
  - Get one at: https://github.com/settings/tokens
  - Required scope: `admin:public_key`

## ✅ What Gets Installed

- ✅ SSH Server (OpenSSH)
- ✅ SSH Key Pair (Ed25519)
- ✅ GitHub SSH Configuration
- ✅ Git Configuration
- ✅ Security Hardening

## 🧪 Verify Installation

```bash
# Test GitHub connection
ssh -T git@github.com

# Expected output:
# Hi username! You've successfully authenticated...
```

## 📚 Full Documentation

See [VPS_SETUP.md](VPS_SETUP.md) for complete documentation.

## 🆘 Troubleshooting

**SSH connection fails?**
- Check: https://github.com/settings/keys (if manual setup)
- Verify key was added correctly
- Test: `ssh -T -v git@github.com`

**Permission denied?**
- Ensure script was run with `sudo`
- Check SSH key permissions: `ls -la ~/.ssh/`

**Need help?**
- See full docs: [VPS_SETUP.md](VPS_SETUP.md)
- Check GitHub SSH docs: https://docs.github.com/en/authentication/connecting-to-github-with-ssh

---

**That's it!** Your VPS is now ready for GitHub development. 🎉

