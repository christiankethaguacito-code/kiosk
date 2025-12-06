#!/bin/bash
#############################################################
# SKSU Campus Kiosk - USB Auto-Installer
# This script runs AUTOMATICALLY when USB is plugged in
# NO USER INTERACTION REQUIRED
# Version 2.0 - Bulletproof auto-detection
#############################################################

# Get the directory where this script is located (USB mount point)
# This auto-detects regardless of where USB is mounted
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
LOG_FILE="$SCRIPT_DIR/auto-install.log"

# Auto-detect current user and home
CURRENT_USER="${USER:-pi}"
USER_HOME="${HOME:-/home/pi}"

# Initialize log
echo "=== SKSU Kiosk Auto-Install Started: $(date) ===" > "$LOG_FILE"

# Function to log messages
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Function to show notification (fails silently if no display)
show_notification() {
    local title="$1"
    local text="$2"
    local timeout="${3:-10}"
    
    if [ -n "$DISPLAY" ] && command -v zenity &> /dev/null; then
        zenity --info --title="$title" --text="$text" --timeout="$timeout" 2>/dev/null || true
    fi
    log_message "NOTIFICATION: $title - $text"
}

log_message "Script started from: $SCRIPT_DIR"
log_message "Current user: $CURRENT_USER"
log_message "User home: $USER_HOME"

# Check if already installed (check in user's home directory)
INSTALL_MARKER="$USER_HOME/.kiosk-installed"
if [ -f "$INSTALL_MARKER" ]; then
    log_message "System already installed. Offering update..."
    show_notification "SKSU Kiosk" "Kiosk already installed.\n\nStarting update process..." 5
    
    # Run update script instead
    if [ -f "$SCRIPT_DIR/update-from-usb.sh" ]; then
        bash "$SCRIPT_DIR/update-from-usb.sh" >> "$LOG_FILE" 2>&1
    elif [ -f "$USER_HOME/kiosk-update.sh" ]; then
        log_message "Using installed update script"
        bash "$USER_HOME/kiosk-update.sh" >> "$LOG_FILE" 2>&1
    else
        log_message "No update script found, running full deployment"
        bash "$SCRIPT_DIR/deploy-kiosk.sh" >> "$LOG_FILE" 2>&1
    fi
    
    show_notification "Update Complete" "SKSU Kiosk updated successfully!" 10
    exit 0
fi

log_message "Starting new installation..."

# Show installation notification
show_notification "SKSU Kiosk Auto-Installer" "USB Drive Detected!\n\nAutomatic installation starting...\n\nPlease wait 5-10 minutes.\n\nDO NOT remove USB drive." 10

# Check if deploy script exists
if [ ! -f "$SCRIPT_DIR/deploy-kiosk.sh" ]; then
    log_message "ERROR: deploy-kiosk.sh not found!"
    show_notification "Installation Error" "deploy-kiosk.sh not found on USB drive.\n\nPlease check USB contents." 15
    exit 1
fi

# Make deploy script executable
chmod +x "$SCRIPT_DIR/deploy-kiosk.sh" 2>> "$LOG_FILE"

# Run deployment script
log_message "Running deploy-kiosk.sh..."
if bash "$SCRIPT_DIR/deploy-kiosk.sh" >> "$LOG_FILE" 2>&1; then
    log_message "Deployment successful!"
    
    # Mark as installed (in user's home directory)
    touch "$INSTALL_MARKER" 2>> "$LOG_FILE" || sudo touch "$INSTALL_MARKER" 2>> "$LOG_FILE"
    sudo chown $CURRENT_USER:$CURRENT_USER "$INSTALL_MARKER" 2>/dev/null || true
    log_message "Installation marker created: $INSTALL_MARKER"
    
    # Show completion notification
    show_notification "Installation Complete" "SKSU Campus Kiosk installed successfully!\n\nSystem will reboot in 10 seconds..." 10
    
    # Copy log to user home for reference
    cp "$LOG_FILE" "$USER_HOME/kiosk-installation.log" 2>/dev/null || true
    
    # Wait a bit for user to see notification
    sleep 10
    
    # Auto-reboot
    log_message "Rebooting system..."
    sudo reboot || {
        log_message "Reboot failed. Please reboot manually."
        show_notification "Installation Complete" "Please reboot your Raspberry Pi manually to complete setup." 30
        exit 0
    }
else
    log_message "ERROR: Deployment failed!"
    log_message "Check $LOG_FILE for details"
    
    # Copy log to user home for debugging
    cp "$LOG_FILE" "$USER_HOME/kiosk-installation-error.log" 2>/dev/null || true
    
    show_notification "Installation Failed" "An error occurred during installation.\n\nCheck auto-install.log or ~/kiosk-installation-error.log for details." 30
    exit 1
fi
