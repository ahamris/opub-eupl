# VPS Setup Script - SSH & GitHub Configuration

A comprehensive bash script to automatically install SSH server and configure GitHub access on any clean Linux VPS.

## 🚀 Features

- **SSH Server Installation**: Automatically installs and configures OpenSSH server
- **SSH Key Generation**: Creates secure SSH keys (Ed25519 or RSA) for GitHub
- **GitHub Integration**: Automatically adds SSH key to GitHub (optional)
- **Security Hardening**: Configures SSH with security best practices
- **Git Configuration**: Sets up Git with your GitHub credentials
- **Multi-Distribution Support**: Works on Ubuntu, Debian, Fedora, RHEL, CentOS, Arch Linux

## 📋 Prerequisites

- Clean Linux VPS (Ubuntu, Debian, Fedora, RHEL, CentOS, or Arch)
- Root or sudo access
- Internet connection
- (Optional) GitHub personal access token for automatic key upload

## 🔧 Installation

1. **Download the script:**
   ```bash
   wget https://raw.githubusercontent.com/YOUR_USERNAME/YOUR_REPO/main/vps-setup.sh
   # Or copy the script to your VPS
   ```

2. **Make it executable:**
   ```bash
   chmod +x vps-setup.sh
   ```

3. **Run the script:**
   ```bash
   sudo ./vps-setup.sh --github-username YOUR_USERNAME --github-email YOUR_EMAIL
   ```

## 📖 Usage

### Basic Setup (Manual GitHub Key Upload)

```bash
sudo ./vps-setup.sh \
  --github-username your-username \
  --github-email your-email@example.com
```

After running, you'll need to manually add the SSH key to GitHub:
1. Copy the displayed public key
2. Go to https://github.com/settings/keys
3. Click "New SSH key"
4. Paste and save

### Automatic Setup (With GitHub Token)

```bash
sudo ./vps-setup.sh \
  --github-token ghp_your_personal_access_token \
  --github-username your-username \
  --github-email your-email@example.com
```

The script will automatically add your SSH key to GitHub.

### Custom SSH Key Options

```bash
sudo ./vps-setup.sh \
  --github-username your-username \
  --github-email your-email@example.com \
  --ssh-key-name my_custom_key \
  --ssh-key-type rsa
```

## 🔑 GitHub Personal Access Token

To enable automatic SSH key upload, you need a GitHub personal access token:

1. Go to: https://github.com/settings/tokens
2. Click "Generate new token (classic)"
3. Select scopes:
   - `admin:public_key` (required for adding SSH keys)
4. Generate and copy the token
5. Use it with `--github-token` option

**Security Note**: Never commit tokens to version control. Use environment variables or secure storage.

## 📝 Options

| Option | Description | Required |
|--------|-------------|----------|
| `--github-username` | Your GitHub username | Recommended |
| `--github-email` | Your GitHub email | Recommended |
| `--github-token` | GitHub personal access token | Optional (for auto-upload) |
| `--ssh-key-name` | SSH key filename (default: `id_ed25519`) | Optional |
| `--ssh-key-type` | SSH key type: `ed25519` or `rsa` (default: `ed25519`) | Optional |
| `-h, --help` | Show help message | Optional |

## 🔒 Security Features

The script automatically configures SSH with security best practices:

- ✅ **Root login disabled**: Prevents direct root access via SSH
- ✅ **Public key authentication**: Enabled for secure access
- ✅ **Password authentication**: Enabled (you can disable manually if needed)
- ✅ **Connection limits**: Max 3 authentication attempts
- ✅ **Idle timeout**: Disconnects idle sessions after 5 minutes
- ✅ **X11 forwarding**: Disabled for security

## 🧪 Testing

After setup, test your GitHub SSH connection:

```bash
# Switch to the configured user
su - username

# Test GitHub connection
ssh -T git@github.com
```

You should see:
```
Hi username! You've successfully authenticated, but GitHub does not provide shell access.
```

## 📁 Files Created

- `~/.ssh/id_ed25519` - Private SSH key (keep secure!)
- `~/.ssh/id_ed25519.pub` - Public SSH key (safe to share)
- `~/.ssh/config` - SSH configuration for GitHub
- `/etc/ssh/sshd_config.backup.*` - Backup of original SSH config

## 🔄 What the Script Does

1. **Detects OS** - Identifies Linux distribution
2. **Installs SSH Server** - Installs OpenSSH server package
3. **Configures SSH** - Applies security hardening settings
4. **Finds/Creates User** - Uses existing user or creates 'deploy' user
5. **Generates SSH Key** - Creates Ed25519 or RSA key pair
6. **Configures SSH for GitHub** - Sets up SSH config file
7. **Configures Git** - Sets Git username and email
8. **Adds Key to GitHub** - (Optional) Uploads key via GitHub API
9. **Tests Connection** - Verifies GitHub SSH access

## 🛠️ Troubleshooting

### SSH Server Not Starting

```bash
# Check SSH service status
systemctl status sshd

# View SSH logs
journalctl -u sshd

# Restart SSH service
systemctl restart sshd
```

### GitHub Connection Fails

1. **Verify key is added to GitHub:**
   - Check: https://github.com/settings/keys
   - Ensure the public key matches your local key

2. **Test SSH connection manually:**
   ```bash
   ssh -T -v git@github.com
   ```

3. **Check SSH config:**
   ```bash
   cat ~/.ssh/config
   ```

4. **Verify key permissions:**
   ```bash
   ls -la ~/.ssh/
   # Should be: -rw------- for private key, -rw-r--r-- for public key
   ```

### Permission Denied Errors

```bash
# Fix SSH directory permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/id_*
chmod 644 ~/.ssh/*.pub
chmod 600 ~/.ssh/config
```

### GitHub Token Issues

- Ensure token has `admin:public_key` scope
- Token must be a "classic" personal access token
- Check token hasn't expired
- Verify token is correct (no extra spaces)

## 🔐 Security Best Practices

1. **Disable Password Authentication** (after setting up keys):
   ```bash
   sudo sed -i 's/#PasswordAuthentication yes/PasswordAuthentication no/' /etc/ssh/sshd_config
   sudo systemctl restart sshd
   ```

2. **Change SSH Port** (optional):
   ```bash
   sudo sed -i 's/#Port 22/Port 2222/' /etc/ssh/sshd_config
   sudo systemctl restart sshd
   ```

3. **Use Firewall**:
   ```bash
   # Ubuntu/Debian
   sudo ufw allow ssh
   sudo ufw enable
   
   # Fedora/RHEL/CentOS
   sudo firewall-cmd --permanent --add-service=ssh
   sudo firewall-cmd --reload
   ```

4. **Keep SSH Updated**:
   ```bash
   # Ubuntu/Debian
   sudo apt update && sudo apt upgrade openssh-server
   
   # Fedora/RHEL/CentOS
   sudo dnf update openssh-server
   ```

## 📚 Examples

### Complete Automated Setup

```bash
# Set variables
export GITHUB_TOKEN="ghp_your_token_here"
export GITHUB_USER="your-username"
export GITHUB_EMAIL="your-email@example.com"

# Run script
sudo ./vps-setup.sh \
  --github-token "$GITHUB_TOKEN" \
  --github-username "$GITHUB_USER" \
  --github-email "$GITHUB_EMAIL"
```

### Setup for Multiple Users

Run the script for each user, or manually:

```bash
# For user1
sudo -u user1 ./vps-setup.sh --github-username user1 --github-email user1@example.com

# For user2
sudo -u user2 ./vps-setup.sh --github-username user2 --github-email user2@example.com
```

## 🆘 Support

If you encounter issues:

1. Check the script output for error messages
2. Review SSH logs: `journalctl -u sshd`
3. Verify all prerequisites are met
4. Ensure you have root/sudo access
5. Check GitHub token permissions

## 📄 License

This script is provided as-is. Use at your own risk. Always review scripts before running them on production systems.

## 🔗 Related Documentation

- [GitHub SSH Setup Guide](https://docs.github.com/en/authentication/connecting-to-github-with-ssh)
- [OpenSSH Documentation](https://www.openssh.com/manual.html)
- [SSH Security Best Practices](https://www.ssh.com/academy/ssh/security)

---

**Last Updated**: 2025-01-XX  
**Version**: 1.0.0

