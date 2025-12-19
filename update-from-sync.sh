#!/bin/bash
#============================================
# UPDATE KIOSK FROM SYNC PACKAGE
# Run this on Raspberry Pi after USB transfer
#============================================

echo "========================================"
echo "  KIOSK UPDATE FROM SYNC PACKAGE"
echo "========================================"
echo ""

# Find the USB mount point
USB_PATH=""
for mount in /media/pi/* /media/$USER/* /mnt/* /media/*; do
    if [ -d "$mount/kiosk-sync" ]; then
        USB_PATH="$mount/kiosk-sync"
        break
    fi
done

if [ -z "$USB_PATH" ]; then
    echo "ERROR: Could not find kiosk-sync folder on USB!"
    echo "Make sure USB is mounted and contains kiosk-sync folder"
    echo ""
    echo "Checking common mount points..."
    ls -la /media/ 2>/dev/null
    ls -la /media/pi/ 2>/dev/null
    ls -la /mnt/ 2>/dev/null
    exit 1
fi

echo "Found sync package at: $USB_PATH"
echo ""

# Get the Navi directory (same as this script)
NAVI_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$NAVI_DIR"
echo "Updating kiosk at: $NAVI_DIR"
echo ""

# Backup current database
echo "[1/6] Backing up current database..."
if [ -f "database/database.sqlite" ]; then
    BACKUP_NAME="database/database.sqlite.backup.$(date +%Y%m%d_%H%M%S)"
    cp "database/database.sqlite" "$BACKUP_NAME"
    echo "  Backup created: $BACKUP_NAME"
else
    echo "  No existing database found"
fi
echo ""

# Copy new database
echo "[2/6] Updating database..."
if [ -f "$USB_PATH/database/database.sqlite" ]; then
    cp "$USB_PATH/database/database.sqlite" "database/database.sqlite"
    chmod 664 "database/database.sqlite"
    echo "  Database updated"
else
    echo "  ERROR: No database found in sync package!"
fi
echo ""

# Ensure storage directories exist
echo "[3/6] Preparing storage directories..."
mkdir -p storage/app/public/announcements
mkdir -p storage/app/public/buildings
echo "  Directories ready"
echo ""

# Copy announcement images
echo "[4/6] Copying announcement images..."
if [ -d "$USB_PATH/storage/announcements" ] && [ "$(ls -A $USB_PATH/storage/announcements 2>/dev/null)" ]; then
    cp -rv "$USB_PATH/storage/announcements/"* "storage/app/public/announcements/"
    echo "  Announcements copied"
else
    echo "  No announcement images in sync package"
fi
echo ""

# Copy building images  
echo "[5/6] Copying building images..."
if [ -d "$USB_PATH/storage/buildings" ] && [ "$(ls -A $USB_PATH/storage/buildings 2>/dev/null)" ]; then
    cp -rv "$USB_PATH/storage/buildings/"* "storage/app/public/buildings/"
    echo "  Buildings copied"
else
    echo "  No building images in sync package"
fi
echo ""

# Fix everything
echo "[6/6] Fixing permissions and symlinks..."

# Fix permissions
chmod -R 775 storage
chmod -R 775 database
chmod -R 775 bootstrap/cache

# Recreate storage symlink
rm -f public/storage 2>/dev/null
php artisan storage:link 2>/dev/null || ln -sf "$NAVI_DIR/storage/app/public" "$NAVI_DIR/public/storage"

# Clear caches
php artisan cache:clear 2>/dev/null
php artisan view:clear 2>/dev/null
php artisan config:clear 2>/dev/null
php artisan route:clear 2>/dev/null

# Set ownership if running as root
if [ "$EUID" -eq 0 ] || [ -n "$SUDO_USER" ]; then
    chown -R www-data:www-data storage database public/storage bootstrap/cache 2>/dev/null
    echo "  Ownership set to www-data"
fi

echo "  Permissions and symlinks fixed"
echo ""

# Verify symlink
echo "Verifying storage symlink..."
if [ -L "public/storage" ]; then
    echo "  ✓ Symlink exists: $(readlink public/storage)"
else
    echo "  ✗ WARNING: Symlink missing, creating manually..."
    ln -sf "$NAVI_DIR/storage/app/public" "$NAVI_DIR/public/storage"
fi

# Count what was synced
echo ""
echo "========================================"
echo "  SYNC SUMMARY"
echo "========================================"
ANNOUNCEMENT_COUNT=$(ls -1 storage/app/public/announcements 2>/dev/null | wc -l)
BUILDING_COUNT=$(ls -1 storage/app/public/buildings 2>/dev/null | wc -l)
echo "  Announcements: $ANNOUNCEMENT_COUNT images"
echo "  Buildings: $BUILDING_COUNT images"
echo "========================================"
echo ""
echo "  UPDATE COMPLETE!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Restart your web server:"
echo "   sudo systemctl restart apache2"
echo "   OR"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "2. Safely eject USB drive"
echo ""
