#!/bin/bash
#############################################################
# SKSU Campus Kiosk - USB Handler
# Automatically detects and runs installer from USB
#############################################################

# Log to syslog for debugging
exec 1> >(logger -s -t kiosk-usb-handler) 2>&1

DEVICE="/dev/$1"

# Validate device parameter
if [ -z "$DEVICE" ] || [ ! -b "$DEVICE" ]; then
    echo "Invalid device: $DEVICE"
    exit 1
fi

echo "USB device detected: $DEVICE"

# Wait for device to be ready and settle
sleep 5

# Check if device still exists after sleep
if [ ! -b "$DEVICE" ]; then
    echo "Device $DEVICE disappeared"
    exit 1
fi

# Create unique mount point
MOUNT_POINT="/media/kiosk-usb-$$"

# Cleanup function
cleanup() {
    if mountpoint -q "$MOUNT_POINT" 2>/dev/null; then
        umount "$MOUNT_POINT" 2>/dev/null
    fi
    if [ -d "$MOUNT_POINT" ]; then
        rmdir "$MOUNT_POINT" 2>/dev/null
    fi
}

# Set trap for cleanup
trap cleanup EXIT

# Create mount point
mkdir -p "$MOUNT_POINT" || {
    echo "Failed to create mount point: $MOUNT_POINT"
    exit 1
}

echo "Attempting to mount $DEVICE at $MOUNT_POINT"

# Try to mount with different filesystems
if mount -t vfat,exfat,ntfs-3g,ext4 "$DEVICE" "$MOUNT_POINT" 2>/dev/null; then
    echo "Successfully mounted $DEVICE"
    
    # Wait a moment for filesystem to settle
    sleep 2
    
    # Check if this is the kiosk USB (look for install.sh)
    if [ -f "$MOUNT_POINT/install.sh" ]; then
        echo "Found install.sh - this is the kiosk USB!"
        
        # Prevent multiple concurrent installations
        LOCK_FILE="/tmp/kiosk-install.lock"
        if [ -f "$LOCK_FILE" ]; then
            echo "Installation already in progress (lock file exists)"
            exit 0
        fi
        
        # Create lock file
        touch "$LOCK_FILE"
        
        # Make install.sh executable
        chmod +x "$MOUNT_POINT/install.sh" 2>/dev/null
        
        # Set display for GUI notifications
        export DISPLAY=:0
        export XAUTHORITY=/home/pi/.Xauthority
        
        # Run installer as pi user in background
        # Don't unmount - installer needs access to USB files
        sudo -u pi bash "$MOUNT_POINT/install.sh" &
        
        # Don't cleanup - let installer handle it
        trap - EXIT
        
        echo "Installer started in background (PID: $!)"
    else
        echo "install.sh not found - not the kiosk USB"
        cleanup
    fi
else
    echo "Failed to mount $DEVICE"
    rmdir "$MOUNT_POINT" 2>/dev/null
    exit 1
fi
