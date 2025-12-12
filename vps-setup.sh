#!/bin/bash

# VPS Setup Script - SSH & GitHub Configuration
# This script automates SSH installation and GitHub setup on a clean VPS
# Usage: ./vps-setup.sh [--github-token TOKEN] [--github-username USERNAME] [--github-email EMAIL]

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Print colored output
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${CYAN}[STEP]${NC} $1"
}

# Variables
GITHUB_TOKEN=""
GITHUB_USERNAME=""
GITHUB_EMAIL=""
SSH_KEY_NAME="id_ed25519"
SSH_KEY_TYPE="ed25519"
AUTO_ADD_KEY=false

# Parse command line arguments
parse_args() {
    while [[ $# -gt 0 ]]; do
        case $1 in
            --github-token)
                GITHUB_TOKEN="$2"
                AUTO_ADD_KEY=true
                shift 2
                ;;
            --github-username)
                GITHUB_USERNAME="$2"
                shift 2
                ;;
            --github-email)
                GITHUB_EMAIL="$2"
                shift 2
                ;;
            --ssh-key-name)
                SSH_KEY_NAME="$2"
                shift 2
                ;;
            --ssh-key-type)
                SSH_KEY_TYPE="$2"
                shift 2
                ;;
            -h|--help)
                show_help
                exit 0
                ;;
            *)
                print_error "Unknown option: $1"
                show_help
                exit 1
                ;;
        esac
    done
}

show_help() {
    cat << EOF
VPS Setup Script - SSH & GitHub Configuration

Usage: $0 [OPTIONS]

Options:
    --github-token TOKEN      GitHub personal access token (for automatic key upload)
    --github-username USER    GitHub username
    --github-email EMAIL      GitHub email address
    --ssh-key-name NAME       SSH key filename (default: id_ed25519)
    --ssh-key-type TYPE       SSH key type: ed25519 or rsa (default: ed25519)
    -h, --help               Show this help message

Examples:
    # Basic setup (manual GitHub key upload)
    $0 --github-username myuser --github-email user@example.com

    # Automatic setup with GitHub token
    $0 --github-token ghp_xxxxx --github-username myuser --github-email user@example.com

Notes:
    - If --github-token is provided, the SSH key will be automatically added to GitHub
    - GitHub token needs 'admin:public_key' scope for automatic key upload
    - If no token is provided, you'll get instructions to add the key manually
EOF
}

# Check if running as root (required for SSH installation)
check_root() {
    if [ "$EUID" -ne 0 ]; then 
        print_error "This script must be run as root for SSH installation"
        print_info "Please run: sudo $0"
        exit 1
    fi
}

# Detect OS
detect_os() {
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        if [ -f /etc/os-release ]; then
            . /etc/os-release
            OS=$ID
            OS_VERSION=$VERSION_ID
        else
            print_error "Cannot detect OS version"
            exit 1
        fi
    else
        print_error "Unsupported OS: $OSTYPE (This script is for Linux VPS)"
        exit 1
    fi
    print_info "Detected OS: $OS $OS_VERSION"
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Install SSH server
install_ssh_server() {
    print_step "Installing SSH server..."
    
    if command_exists sshd && systemctl is-active --quiet sshd 2>/dev/null; then
        print_success "SSH server is already installed and running"
        return 0
    fi

    if [ "$OS" == "ubuntu" ] || [ "$OS" == "debian" ]; then
        apt-get update
        apt-get install -y openssh-server
    elif [ "$OS" == "fedora" ] || [ "$OS" == "rhel" ] || [ "$OS" == "centos" ]; then
        if command_exists dnf; then
            dnf install -y openssh-server
        else
            yum install -y openssh-server
        fi
    elif [ "$OS" == "arch" ]; then
        pacman -S --noconfirm openssh
    else
        print_error "Automatic SSH installation not supported for $OS"
        print_info "Please install openssh-server manually"
        exit 1
    fi

    # Enable and start SSH service
    systemctl enable sshd
    systemctl start sshd
    
    print_success "SSH server installed and started"
}

# Configure SSH server (security hardening)
configure_ssh_server() {
    print_step "Configuring SSH server security..."
    
    SSH_CONFIG="/etc/ssh/sshd_config"
    SSH_CONFIG_BACKUP="/etc/ssh/sshd_config.backup.$(date +%Y%m%d_%H%M%S)"
    
    # Backup original config
    cp "$SSH_CONFIG" "$SSH_CONFIG_BACKUP"
    print_info "Backed up SSH config to $SSH_CONFIG_BACKUP"
    
    # Security configurations
    cat >> "$SSH_CONFIG" << 'EOF'

# Security hardening (added by vps-setup.sh)
PermitRootLogin no
PasswordAuthentication yes
PubkeyAuthentication yes
AuthorizedKeysFile .ssh/authorized_keys
X11Forwarding no
MaxAuthTries 3
ClientAliveInterval 300
ClientAliveCountMax 2
EOF

    # Restart SSH service
    systemctl restart sshd
    
    print_success "SSH server configured with security settings"
    print_warning "Root login disabled. Make sure you have another way to access the server!"
}

# Get the non-root user (or create one)
get_user() {
    # Try to find a non-root user
    if [ -n "$SUDO_USER" ]; then
        USERNAME="$SUDO_USER"
    else
        # Get first regular user from /etc/passwd
        USERNAME=$(awk -F: '$3 >= 1000 && $1 != "nobody" {print $1; exit}' /etc/passwd)
    fi
    
    if [ -z "$USERNAME" ]; then
        print_warning "No regular user found. Creating user 'deploy'..."
        USERNAME="deploy"
        useradd -m -s /bin/bash "$USERNAME"
        print_info "Created user: $USERNAME"
        print_warning "Set password for $USERNAME: passwd $USERNAME"
    fi
    
    USER_HOME=$(eval echo ~$USERNAME)
    print_info "Using user: $USERNAME (home: $USER_HOME)"
}

# Generate SSH key for GitHub
generate_ssh_key() {
    print_step "Generating SSH key for GitHub..."
    
    SSH_DIR="$USER_HOME/.ssh"
    SSH_KEY_PATH="$SSH_DIR/$SSH_KEY_NAME"
    
    # Create .ssh directory if it doesn't exist
    mkdir -p "$SSH_DIR"
    chmod 700 "$SSH_DIR"
    chown "$USERNAME:$USERNAME" "$SSH_DIR"
    
    # Check if key already exists
    if [ -f "$SSH_KEY_PATH" ]; then
        print_warning "SSH key already exists at $SSH_KEY_PATH"
        read -p "Overwrite? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            print_info "Using existing SSH key"
            return 0
        fi
    fi
    
    # Generate SSH key
    if [ "$SSH_KEY_TYPE" == "ed25519" ]; then
        sudo -u "$USERNAME" ssh-keygen -t ed25519 -C "$GITHUB_EMAIL" -f "$SSH_KEY_PATH" -N ""
    elif [ "$SSH_KEY_TYPE" == "rsa" ]; then
        sudo -u "$USERNAME" ssh-keygen -t rsa -b 4096 -C "$GITHUB_EMAIL" -f "$SSH_KEY_PATH" -N ""
    else
        print_error "Unsupported SSH key type: $SSH_KEY_TYPE"
        exit 1
    fi
    
    # Set proper permissions
    chmod 600 "$SSH_KEY_PATH"
    chmod 644 "$SSH_KEY_PATH.pub"
    chown "$USERNAME:$USERNAME" "$SSH_KEY_PATH" "$SSH_KEY_PATH.pub"
    
    print_success "SSH key generated: $SSH_KEY_PATH"
}

# Configure SSH config for GitHub
configure_ssh_for_github() {
    print_step "Configuring SSH for GitHub..."
    
    SSH_CONFIG="$USER_HOME/.ssh/config"
    
    # Create or update SSH config
    if [ ! -f "$SSH_CONFIG" ]; then
        sudo -u "$USERNAME" touch "$SSH_CONFIG"
        chmod 600 "$SSH_CONFIG"
    fi
    
    # Add GitHub configuration if not present
    if ! grep -q "Host github.com" "$SSH_CONFIG" 2>/dev/null; then
        cat >> "$SSH_CONFIG" << EOF

# GitHub configuration (added by vps-setup.sh)
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/$SSH_KEY_NAME
    IdentitiesOnly yes
EOF
        chmod 600 "$SSH_CONFIG"
        chown "$USERNAME:$USERNAME" "$SSH_CONFIG"
        print_success "SSH config updated for GitHub"
    else
        print_info "GitHub configuration already exists in SSH config"
    fi
}

# Add SSH key to GitHub using API
add_key_to_github() {
    if [ "$AUTO_ADD_KEY" != true ] || [ -z "$GITHUB_TOKEN" ]; then
        return 0
    fi
    
    print_step "Adding SSH key to GitHub..."
    
    SSH_KEY_PATH="$USER_HOME/.ssh/$SSH_KEY_NAME.pub"
    PUBLIC_KEY=$(cat "$SSH_KEY_PATH")
    KEY_TITLE="VPS $(hostname) - $(date +%Y-%m-%d)"
    
    # Add SSH key via GitHub API
    RESPONSE=$(curl -s -w "\n%{http_code}" -X POST \
        -H "Authorization: token $GITHUB_TOKEN" \
        -H "Accept: application/vnd.github.v3+json" \
        https://api.github.com/user/keys \
        -d "{\"title\":\"$KEY_TITLE\",\"key\":\"$PUBLIC_KEY\"}")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" == "201" ]; then
        print_success "SSH key added to GitHub successfully"
    elif [ "$HTTP_CODE" == "422" ]; then
        print_warning "SSH key may already exist on GitHub (or invalid key)"
        print_info "You can add it manually using the instructions below"
    else
        print_error "Failed to add SSH key to GitHub (HTTP $HTTP_CODE)"
        print_info "Response: $BODY"
        print_info "You can add it manually using the instructions below"
    fi
}

# Configure Git
configure_git() {
    if [ -z "$GITHUB_USERNAME" ] || [ -z "$GITHUB_EMAIL" ]; then
        print_warning "Skipping Git configuration (username/email not provided)"
        return 0
    fi
    
    print_step "Configuring Git..."
    
    sudo -u "$USERNAME" git config --global user.name "$GITHUB_USERNAME"
    sudo -u "$USERNAME" git config --global user.email "$GITHUB_EMAIL"
    sudo -u "$USERNAME" git config --global init.defaultBranch main
    sudo -u "$USERNAME" git config --global pull.rebase false
    
    print_success "Git configured for user: $GITHUB_USERNAME <$GITHUB_EMAIL>"
}

# Test GitHub SSH connection
test_github_connection() {
    print_step "Testing GitHub SSH connection..."
    
    SSH_KEY_PATH="$USER_HOME/.ssh/$SSH_KEY_NAME"
    
    # Test connection
    if sudo -u "$USERNAME" ssh -T -i "$SSH_KEY_PATH" -o StrictHostKeyChecking=no git@github.com 2>&1 | grep -q "successfully authenticated"; then
        print_success "GitHub SSH connection successful!"
        return 0
    else
        print_warning "GitHub SSH connection test failed or key not added yet"
        return 1
    fi
}

# Display SSH public key
display_ssh_key() {
    SSH_KEY_PATH="$USER_HOME/.ssh/$SSH_KEY_NAME.pub"
    
    if [ -f "$SSH_KEY_PATH" ]; then
        echo ""
        echo "=========================================="
        print_success "SSH Public Key:"
        echo "=========================================="
        cat "$SSH_KEY_PATH"
        echo ""
        echo "=========================================="
    fi
}

# Print summary and instructions
print_summary() {
    SSH_KEY_PATH="$USER_HOME/.ssh/$SSH_KEY_NAME.pub"
    
    echo ""
    echo "=========================================="
    print_success "VPS Setup Completed!"
    echo "=========================================="
    echo ""
    echo "📦 Installed:"
    echo "  ✅ SSH Server (OpenSSH)"
    echo "  ✅ SSH Key Generated"
    echo "  ✅ SSH Config for GitHub"
    if [ -n "$GITHUB_USERNAME" ] && [ -n "$GITHUB_EMAIL" ]; then
        echo "  ✅ Git Configured"
    fi
    echo ""
    echo "👤 User: $USERNAME"
    echo "🔑 SSH Key: $SSH_KEY_PATH"
    echo ""
    
    if [ "$AUTO_ADD_KEY" != true ] || [ -z "$GITHUB_TOKEN" ]; then
        echo "📋 Next Steps - Add SSH Key to GitHub:"
        echo ""
        echo "1. Copy your SSH public key:"
        echo "   cat $SSH_KEY_PATH"
        echo ""
        echo "2. Go to GitHub: https://github.com/settings/keys"
        echo ""
        echo "3. Click 'New SSH key'"
        echo ""
        echo "4. Paste your public key and save"
        echo ""
        echo "5. Test connection:"
        echo "   sudo -u $USERNAME ssh -T git@github.com"
        echo ""
    else
        echo "✅ SSH key automatically added to GitHub"
        echo ""
        echo "🧪 Test connection:"
        echo "   sudo -u $USERNAME ssh -T git@github.com"
        echo ""
    fi
    
    echo "🔒 SSH Server Security:"
    echo "  - Root login: Disabled"
    echo "  - Password auth: Enabled"
    echo "  - Public key auth: Enabled"
    echo ""
    echo "📝 Useful Commands:"
    echo "  - View SSH status: systemctl status sshd"
    echo "  - View SSH logs: journalctl -u sshd"
    echo "  - Restart SSH: systemctl restart sshd"
    echo "  - Test GitHub: sudo -u $USERNAME ssh -T git@github.com"
    echo ""
}

# Main installation flow
main() {
    echo "=========================================="
    echo "VPS Setup - SSH & GitHub Configuration"
    echo "=========================================="
    echo ""
    
    parse_args "$@"
    
    check_root
    detect_os
    install_ssh_server
    configure_ssh_server
    get_user
    generate_ssh_key
    configure_ssh_for_github
    configure_git
    add_key_to_github
    display_ssh_key
    
    # Wait a moment for GitHub API to process
    if [ "$AUTO_ADD_KEY" == true ]; then
        sleep 2
        test_github_connection || true
    fi
    
    print_summary
}

# Run main function
main "$@"

