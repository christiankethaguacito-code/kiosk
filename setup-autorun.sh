#!/bin/bash
#############################################################
# SKSU Campus Kiosk - Setup Auto-Run System
# Run this ONCE on Raspberry Pi to enable plug-and-play
#############################################################

echo "╔════════════════════════════════════════════════════════╗"
echo "║   SKSU Campus Kiosk - Auto-Run Setup                  ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "This will configure your Raspberry Pi to automatically"
echo "install the kiosk when you plug in the USB drive."
echo ""
echo "⚠️  You only need to run this ONCE per Raspberry Pi"
echo ""
read -p "Continue? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Setup cancelled."
    exit 0
fi

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo ""
echo "🔍 Checking for existing installation..."
if [ -f "/etc/udev/rules.d/99-kiosk-usb.rules" ]; then
    echo "⚠️  Auto-run already setup!"
    read -p "Re-install anyway? (y/N): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Setup cancelled."
        exit 0
    fi
fi

echo ""
echo "📦 Installing required packages..."
if ! sudo apt-get update -qq; then
    echo "⚠️  Warning: apt-get update failed. Continuing anyway..."
fi

if ! sudo apt-get install -y zenity udev > /dev/null 2>&1; then
    echo "⚠️  Warning: Some packages may not have installed correctly"
fi
echo "✅ Packages installed"

echo ""
echo "📝 Creating USB handler script..."
if [ ! -f "$SCRIPT_DIR/kiosk-usb-handler.sh" ]; then
    echo "❌ ERROR: kiosk-usb-handler.sh not found!"
    exit 1
fi

sudo cp "$SCRIPT_DIR/kiosk-usb-handler.sh" /usr/local/bin/kiosk-usb-handler.sh
sudo chmod +x /usr/local/bin/kiosk-usb-handler.sh

if [ ! -x "/usr/local/bin/kiosk-usb-handler.sh" ]; then
    echo "❌ ERROR: Failed to create handler script"
    exit 1
fi
echo "✅ Handler script created"

echo ""
echo "⚙️  Creating udev rule..."
if [ ! -f "$SCRIPT_DIR/99-kiosk-usb.rules" ]; then
    echo "❌ ERROR: 99-kiosk-usb.rules not found!"
    exit 1
fi

sudo cp "$SCRIPT_DIR/99-kiosk-usb.rules" /etc/udev/rules.d/99-kiosk-usb.rules
sudo chmod 644 /etc/udev/rules.d/99-kiosk-usb.rules

if [ ! -f "/etc/udev/rules.d/99-kiosk-usb.rules" ]; then
    echo "❌ ERROR: Failed to create udev rule"
    exit 1
fi
echo "✅ Udev rule created"

echo ""
echo "🔄 Reloading udev rules..."
if ! sudo udevadm control --reload-rules; then
    echo "⚠️  Warning: Failed to reload udev rules. May need reboot."
fi

if ! sudo udevadm trigger; then
    echo "⚠️  Warning: Failed to trigger udev. May need reboot."
fi
echo "✅ Udev rules reloaded"

echo ""
echo "╔════════════════════════════════════════════════════════╗"
echo "║   SETUP COMPLETE!                                      ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "✅ Your Raspberry Pi is now ready for plug-and-play!"
echo ""
echo "Next steps:"
echo "1. Safely eject this USB drive"
echo "2. Copy the entire Navi folder to a USB drive"
echo "3. Plug the USB into this Raspberry Pi"
echo "4. Installation will start AUTOMATICALLY!"
echo ""
echo "🎉 No terminal commands needed - just plug and go!"
echo ""
