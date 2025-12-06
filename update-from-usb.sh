#!/bin/bash
#############################################################
# SKSU Campus Kiosk - Update Script
# Updates the kiosk system from USB drive
#############################################################

set -e

echo "╔════════════════════════════════════════════════════════╗"
echo "║   SKSU Campus Kiosk - System Update                   ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Find USB drive
echo "🔍 Looking for USB drive..."
USB_PATH=$(findmnt -t vfat,exfat,ntfs -o TARGET -n | grep -E '/media|/mnt' | head -n 1)

if [ -z "$USB_PATH" ]; then
    echo "❌ ERROR: USB drive not found"
    echo "   Please ensure USB drive is properly connected"
    exit 1
fi

echo "✅ Found USB at: $USB_PATH"
echo ""

PROJECT_DIR="/home/pi/sksu-kiosk"

# Backup current system
echo "📦 Creating backup..."
BACKUP_DIR="/home/pi/kiosk-backups/backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"
sudo cp -r "$PROJECT_DIR/.env" "$BACKUP_DIR/"
sudo cp "$PROJECT_DIR/database/database.sqlite" "$BACKUP_DIR/database.sqlite"
echo "✅ Backup saved to: $BACKUP_DIR"
echo ""

# Stop services
echo "🛑 Stopping kiosk services..."
pkill -f chromium 2>/dev/null || true
pkill -f chromium-browser 2>/dev/null || true
sudo systemctl stop nginx
echo "✅ Services stopped"
echo ""

# Update files
echo "📥 Updating files from USB..."
sudo rsync -av --exclude='storage/app' --exclude='storage/logs' --exclude='.env' --exclude='database/database.sqlite' "$USB_PATH/" "$PROJECT_DIR/"
sudo chown -R www-data:www-data "$PROJECT_DIR"
echo "✅ Files updated"
echo ""

# Update database if provided
if [ -f "$USB_PATH/database/database.sqlite" ]; then
    echo "🗄️  Updating database..."
    read -p "   Replace existing database? This will overwrite current data! (y/N): " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        sudo cp "$USB_PATH/database/database.sqlite" "$PROJECT_DIR/database/database.sqlite"
        sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"
        sudo chown www-data:www-data "$PROJECT_DIR/database/database.sqlite"
        echo "✅ Database updated"
    else
        echo "⏭️  Database update skipped"
    fi
    echo ""
fi

# Update dependencies
echo "📚 Updating dependencies..."
cd "$PROJECT_DIR"
sudo -u www-data composer install --no-dev --optimize-autoloader
echo "✅ Dependencies updated"
echo ""

# Clear caches
echo "🧹 Clearing caches..."
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
echo "✅ Caches cleared"
echo ""

# Optimize
echo "⚡ Optimizing application..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
echo "✅ Application optimized"
echo ""

# Restart services
echo "🚀 Restarting kiosk services..."
sudo systemctl start nginx
sleep 2

# Auto-detect chromium command
CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -n "$CHROMIUM_CMD" ]; then
    DISPLAY=:0 $CHROMIUM_CMD --kiosk --app=http://localhost/ &
    echo "✅ Services restarted"
else
    echo "⚠️  Chromium not found - restart manually or reboot"
fi
echo ""

echo "╔════════════════════════════════════════════════════════╗"
echo "║   UPDATE COMPLETED SUCCESSFULLY                        ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""
echo "✅ System updated from USB"
echo "📋 Backup location: $BACKUP_DIR"
echo "🌐 Kiosk is now running with latest version"
echo ""
