#!/bin/bash
#############################################################
# SKSU Campus Kiosk - Auto-Run Script
# Starts automatic installation process
#############################################################

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "╔════════════════════════════════════════════════════════╗"
echo "║   SKSU Campus Kiosk - Installation Launcher           ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "Choose installation mode:"
echo ""
echo "1) 🚀 AUTOMATIC - Plug-and-Play (Recommended)"
echo "   → Setup auto-run, then just plug USB anytime"
echo ""
echo "2) 📦 MANUAL - Install Now"
echo "   → Run installation immediately"
echo ""
read -p "Select option (1 or 2): " -n 1 -r
echo ""
echo ""

case $REPLY in
    1)
        echo "Setting up automatic plug-and-play mode..."
        chmod +x "$SCRIPT_DIR/setup-autorun.sh"
        exec bash "$SCRIPT_DIR/setup-autorun.sh"
        ;;
    2)
        echo "Starting manual installation..."
        chmod +x "$SCRIPT_DIR/deploy-kiosk.sh"
        exec bash "$SCRIPT_DIR/deploy-kiosk.sh"
        ;;
    *)
        echo "Invalid option. Exiting."
        exit 1
        ;;
esac
