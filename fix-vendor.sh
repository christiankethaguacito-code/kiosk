#!/bin/bash
#############################################################
# FIX CORRUPTED VENDOR DIRECTORY
# The composer autoload files are broken - need to reinstall
#############################################################

echo "============================================"
echo "   Fixing Corrupted Vendor Directory"
echo "============================================"
echo ""

PROJECT_DIR="/home/pi/sksu-kiosk"
cd "$PROJECT_DIR"

echo "📁 Current directory: $(pwd)"
echo ""

# Step 1: Stop services temporarily
echo "🛑 Stopping services..."
sudo systemctl stop nginx
pkill -f chromium 2>/dev/null || true

# Step 2: Delete corrupted vendor directory
echo "🗑️  Removing corrupted vendor directory..."
sudo rm -rf "$PROJECT_DIR/vendor"
sudo rm -f "$PROJECT_DIR/composer.lock"
echo "✅ Old vendor removed"

# Step 3: Clear composer cache
echo "🧹 Clearing composer cache..."
sudo -u www-data composer clear-cache 2>/dev/null || composer clear-cache || true

# Step 4: Reinstall dependencies
echo ""
echo "📦 Reinstalling composer dependencies..."
echo "   This may take a few minutes..."
echo ""

# Set proper ownership first
sudo chown -R www-data:www-data "$PROJECT_DIR"

# Install fresh
cd "$PROJECT_DIR"
sudo -u www-data composer install --no-dev --optimize-autoloader --no-cache

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Composer install successful!"
else
    echo ""
    echo "⚠️  Trying alternative method..."
    # Try as root if www-data fails
    sudo composer install --no-dev --optimize-autoloader --no-cache
fi

# Step 5: Fix permissions again
echo ""
echo "🔐 Fixing permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

# Step 6: Clear Laravel caches
echo ""
echo "🧹 Clearing Laravel cache..."
cd "$PROJECT_DIR"
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear

# Step 7: Restart services
echo ""
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# Step 8: Test
echo ""
echo "🧪 Testing Laravel..."
sleep 2

RESPONSE=$(curl -s http://localhost/)
if echo "$RESPONSE" | grep -q "<!DOCTYPE\|<html\|<!doctype"; then
    echo "✅ Laravel is returning HTML!"
    echo ""
    echo "First 300 characters:"
    echo "---------------------"
    echo "$RESPONSE" | head -c 300
    echo ""
else
    echo "⚠️  Response check:"
    echo "$RESPONSE" | head -c 200
fi

# Step 9: Start kiosk
echo ""
echo "🖥️  Starting kiosk browser..."

CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -n "$CHROMIUM_CMD" ]; then
    DISPLAY=:0 $CHROMIUM_CMD --kiosk --noerrdialogs --no-first-run --app=http://localhost/ &
    echo "✅ Browser started"
fi

echo ""
echo "============================================"
echo "   Fix Complete!"
echo "   Your kiosk should now display properly"
echo "============================================"
