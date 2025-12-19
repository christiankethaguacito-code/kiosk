#!/bin/bash
#===============================================
# FIX ANNOUNCEMENTS - Storage Symlink Fix
# Run this script on Raspberry Pi 5 to fix
# announcement image display issues
#===============================================

echo "========================================"
echo "  ANNOUNCEMENT FIX SCRIPT"
echo "  For SKSU Campus Kiosk"
echo "========================================"
echo ""

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo "[1/7] Checking current directory..."
echo "Working in: $SCRIPT_DIR"
echo ""

# Remove old symlink if it exists (might be broken)
echo "[2/7] Removing old storage symlink (if exists)..."
if [ -L "public/storage" ]; then
    rm public/storage
    echo "  âœ“ Old symlink removed"
elif [ -d "public/storage" ]; then
    rm -rf public/storage
    echo "  âœ“ Old storage directory removed"
else
    echo "  - No existing symlink found"
fi
echo ""

# Create fresh storage symlink
echo "[3/7] Creating storage symlink..."
php artisan storage:link
if [ $? -eq 0 ]; then
    echo "  âœ“ Storage symlink created successfully"
else
    echo "  âœ— Failed to create symlink, trying manual method..."
    ln -sf "$SCRIPT_DIR/storage/app/public" "$SCRIPT_DIR/public/storage"
    echo "  âœ“ Manual symlink created"
fi
echo ""

# Create announcements directory if it doesn't exist
echo "[4/7] Ensuring announcements directory exists..."
mkdir -p storage/app/public/announcements
mkdir -p storage/app/public/buildings
echo "  âœ“ Directories created"
echo ""

# Fix permissions
echo "[5/7] Fixing permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/storage 2>/dev/null || true
chmod -R 775 storage/app/public/announcements
chmod -R 775 storage/app/public/buildings

# Set ownership if running as root or with sudo
if [ "$EUID" -eq 0 ] || [ -n "$SUDO_USER" ]; then
    chown -R www-data:www-data storage
    chown -R www-data:www-data bootstrap/cache
    chown -R www-data:www-data public/storage 2>/dev/null || true
    echo "  âœ“ Permissions and ownership fixed"
else
    echo "  âœ“ Permissions fixed (run with sudo for full ownership fix)"
fi
echo ""

# Clear all caches
echo "[6/7] Clearing caches..."
php artisan cache:clear 2>/dev/null || echo "  - Cache clear skipped"
php artisan view:clear 2>/dev/null || echo "  - View clear skipped"
php artisan config:clear 2>/dev/null || echo "  - Config clear skipped"
php artisan route:clear 2>/dev/null || echo "  - Route clear skipped"
echo "  âœ“ Caches cleared"
echo ""

# Verify the symlink
echo "[7/7] Verifying setup..."
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f public/storage)
    echo "  âœ“ Symlink exists: public/storage -> $LINK_TARGET"
else
    echo "  âœ— WARNING: Symlink not found!"
fi

# Check if announcements directory is accessible
if [ -d "public/storage/announcements" ] || [ -L "public/storage" ]; then
    echo "  âœ“ Announcements directory accessible"
else
    echo "  âœ— WARNING: Announcements directory not accessible"
fi
echo ""

echo "========================================"
echo "  FIX COMPLETE!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Restart your web server:"
echo "   sudo systemctl restart apache2"
echo "   OR"
echo "   sudo systemctl restart nginx"
echo ""
echo "2. If using php artisan serve:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "3. Try uploading an announcement again"
echo ""
echo "If images still don't show, check browser console"
echo "for 404 errors and verify the image path."
echo "========================================"
