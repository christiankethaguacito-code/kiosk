#!/bin/bash
#############################################################
# COMPLETE SYSTEM CHECK & FIX
# Ensures everything runs smoothly
#############################################################

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PROJECT_DIR="/home/pi/sksu-kiosk"

print_ok() { echo -e "${GREEN}✅ $1${NC}"; }
print_fail() { echo -e "${RED}❌ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }
print_warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }

echo ""
echo "============================================"
echo "   Complete System Check & Fix"
echo "============================================"
echo ""

# Stop everything first
print_info "Stopping services..."
pkill -f chromium 2>/dev/null || true
sleep 1

#############################################################
# STEP 1: Verify Project Structure
#############################################################
print_info "Checking project structure..."

if [ ! -d "$PROJECT_DIR" ]; then
    print_fail "Project not found! Run deploy-kiosk.sh first"
    exit 1
fi

if [ ! -f "$PROJECT_DIR/artisan" ]; then
    print_fail "Laravel artisan missing!"
    exit 1
fi

if [ ! -d "$PROJECT_DIR/vendor" ]; then
    print_fail "Vendor directory missing! Run deploy-kiosk.sh first"
    exit 1
fi

print_ok "Project structure OK"

#############################################################
# STEP 2: Check and Fix Views Folder
#############################################################
print_info "Checking views folder case..."
cd "$PROJECT_DIR/resources/views"

if [ -d "Layouts" ]; then
    print_warn "Found 'Layouts' with capital L - fixing..."
    sudo mv Layouts layouts_temp
    sudo mv layouts_temp layouts
    print_ok "Fixed to lowercase 'layouts'"
elif [ -d "layouts" ]; then
    print_ok "Views folder already lowercase"
else
    print_fail "layouts folder not found!"
    exit 1
fi

# Verify critical view files exist
if [ ! -f "$PROJECT_DIR/resources/views/layouts/dual-mode.blade.php" ]; then
    print_fail "layouts/dual-mode.blade.php missing!"
    exit 1
fi

if [ ! -f "$PROJECT_DIR/resources/views/kiosk/welcome.blade.php" ]; then
    print_fail "kiosk/welcome.blade.php missing!"
    exit 1
fi

print_ok "All view files present"

#############################################################
# STEP 3: Check Database
#############################################################
print_info "Checking database..."

if [ ! -f "$PROJECT_DIR/database/database.sqlite" ]; then
    print_warn "Database missing - creating..."
    sudo touch "$PROJECT_DIR/database/database.sqlite"
fi

sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"
sudo chown www-data:www-data "$PROJECT_DIR/database/database.sqlite"
print_ok "Database OK"

#############################################################
# STEP 4: Check .env Configuration
#############################################################
print_info "Checking .env configuration..."

if [ ! -f "$PROJECT_DIR/.env" ]; then
    print_fail ".env file missing!"
    exit 1
fi

# Ensure correct configuration
cd "$PROJECT_DIR"
sudo sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sudo sed -i "s|DB_DATABASE=.*|DB_DATABASE=$PROJECT_DIR/database/database.sqlite|" .env
sudo sed -i 's/^DB_HOST=/#DB_HOST=/' .env
sudo sed -i 's/^DB_PORT=/#DB_PORT=/' .env

print_ok ".env configured"

#############################################################
# STEP 5: Fix All Permissions
#############################################################
print_info "Fixing permissions..."

sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"

print_ok "Permissions fixed"

#############################################################
# STEP 6: Test PHP Syntax of Models
#############################################################
print_info "Testing PHP files syntax..."

# Test Announcement model
if sudo -u www-data php -l "$PROJECT_DIR/app/Models/Announcement.php" > /dev/null 2>&1; then
    print_ok "Announcement.php syntax OK"
else
    print_fail "Announcement.php has syntax errors!"
    sudo -u www-data php -l "$PROJECT_DIR/app/Models/Announcement.php"
    exit 1
fi

# Test KioskController
if sudo -u www-data php -l "$PROJECT_DIR/app/Http/Controllers/KioskController.php" > /dev/null 2>&1; then
    print_ok "KioskController.php syntax OK"
else
    print_fail "KioskController.php has syntax errors!"
    sudo -u www-data php -l "$PROJECT_DIR/app/Http/Controllers/KioskController.php"
    exit 1
fi

#############################################################
# STEP 7: Clear All Caches Multiple Times
#############################################################
print_info "Clearing all caches..."

cd "$PROJECT_DIR"

# Clear multiple times to ensure
for i in {1..2}; do
    sudo -u www-data php artisan config:clear 2>/dev/null || true
    sudo -u www-data php artisan cache:clear 2>/dev/null || true
    sudo -u www-data php artisan view:clear 2>/dev/null || true
    sudo -u www-data php artisan route:clear 2>/dev/null || true
done

# Clear compiled views manually
sudo rm -rf "$PROJECT_DIR/storage/framework/views/"*.php 2>/dev/null || true

print_ok "Caches cleared"

#############################################################
# STEP 8: Check PHP-FPM
#############################################################
print_info "Checking PHP-FPM..."

sudo systemctl restart php8.2-fpm
sleep 2

if systemctl is-active --quiet php8.2-fpm; then
    print_ok "PHP-FPM running"
else
    print_fail "PHP-FPM failed to start!"
    sudo systemctl status php8.2-fpm
    exit 1
fi

if [ -S /var/run/php/php8.2-fpm.sock ]; then
    print_ok "PHP-FPM socket exists"
else
    print_fail "PHP-FPM socket missing!"
    exit 1
fi

#############################################################
# STEP 9: Check Nginx
#############################################################
print_info "Checking Nginx..."

# Test config first
if ! sudo nginx -t > /dev/null 2>&1; then
    print_fail "Nginx configuration error!"
    sudo nginx -t
    exit 1
fi

sudo systemctl restart nginx
sleep 2

if systemctl is-active --quiet nginx; then
    print_ok "Nginx running"
else
    print_fail "Nginx failed to start!"
    sudo systemctl status nginx
    exit 1
fi

#############################################################
# STEP 10: Comprehensive HTTP Tests
#############################################################
print_info "Testing all routes..."

sleep 2

# Test homepage
print_info "Testing homepage..."
HOME_CODE=$(curl -s -o /tmp/test-home.html -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")
if [ "$HOME_CODE" = "200" ]; then
    print_ok "Homepage: 200 OK"
    # Check if it's HTML
    if head -1 /tmp/test-home.html | grep -qi "<!DOCTYPE\|<html"; then
        print_ok "Homepage returns valid HTML"
    else
        print_warn "Homepage response might not be HTML"
    fi
else
    print_fail "Homepage: HTTP $HOME_CODE"
    head -100 /tmp/test-home.html
fi

# Test login page
print_info "Testing login page..."
LOGIN_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/login 2>/dev/null || echo "000")
if [ "$LOGIN_CODE" = "200" ]; then
    print_ok "Login page: 200 OK"
else
    print_fail "Login page: HTTP $LOGIN_CODE"
fi

# Test map page
print_info "Testing map page..."
MAP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/map 2>/dev/null || echo "000")
if [ "$MAP_CODE" = "200" ] || [ "$MAP_CODE" = "503" ]; then
    print_ok "Map page: HTTP $MAP_CODE"
else
    print_fail "Map page: HTTP $MAP_CODE"
fi

#############################################################
# STEP 11: Check Laravel Logs for Errors
#############################################################
print_info "Checking for recent errors..."

if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
    RECENT_ERRORS=$(sudo tail -50 "$PROJECT_DIR/storage/logs/laravel.log" | grep -i "error\|exception\|fatal" | tail -5)
    if [ -n "$RECENT_ERRORS" ]; then
        print_warn "Recent errors found:"
        echo "$RECENT_ERRORS"
    else
        print_ok "No recent errors in Laravel log"
    fi
else
    print_info "No Laravel log file yet"
fi

#############################################################
# STEP 12: Start Kiosk
#############################################################
print_info "Starting kiosk browser..."

CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -n "$CHROMIUM_CMD" ]; then
    DISPLAY=:0 $CHROMIUM_CMD \
        --kiosk \
        --noerrdialogs \
        --disable-infobars \
        --no-first-run \
        --disable-session-crashed-bubble \
        --app=http://localhost/ > /dev/null 2>&1 &
    
    sleep 2
    
    if pgrep -f chromium > /dev/null; then
        print_ok "Kiosk browser started"
    else
        print_warn "Browser may not have started"
    fi
else
    print_warn "Chromium not found"
fi

#############################################################
# SUMMARY
#############################################################
echo ""
echo "============================================"
echo "   System Check Complete"
echo "============================================"
echo ""

ALL_OK=true

echo "Service Status:"
systemctl is-active --quiet php8.2-fpm && echo "  ✅ PHP-FPM: Running" || { echo "  ❌ PHP-FPM: Stopped"; ALL_OK=false; }
systemctl is-active --quiet nginx && echo "  ✅ Nginx: Running" || { echo "  ❌ Nginx: Stopped"; ALL_OK=false; }

echo ""
echo "HTTP Tests:"
[ "$HOME_CODE" = "200" ] && echo "  ✅ Homepage: $HOME_CODE" || { echo "  ❌ Homepage: $HOME_CODE"; ALL_OK=false; }
[ "$LOGIN_CODE" = "200" ] && echo "  ✅ Login: $LOGIN_CODE" || { echo "  ❌ Login: $LOGIN_CODE"; ALL_OK=false; }
[ "$MAP_CODE" = "200" ] || [ "$MAP_CODE" = "503" ] && echo "  ✅ Map: $MAP_CODE" || { echo "  ❌ Map: $MAP_CODE"; ALL_OK=false; }

echo ""
echo "Files:"
[ -d "$PROJECT_DIR/resources/views/layouts" ] && echo "  ✅ Views folder: OK" || { echo "  ❌ Views folder: Missing"; ALL_OK=false; }
[ -f "$PROJECT_DIR/database/database.sqlite" ] && echo "  ✅ Database: OK" || { echo "  ❌ Database: Missing"; ALL_OK=false; }
[ -f "$PROJECT_DIR/.env" ] && echo "  ✅ Environment: OK" || { echo "  ❌ Environment: Missing"; ALL_OK=false; }

echo ""
if [ "$ALL_OK" = true ]; then
    echo "🎉 Everything is running smoothly!"
else
    echo "⚠️  Some issues detected - check details above"
fi

echo ""
echo "Quick Commands:"
echo "  View logs: tail -f $PROJECT_DIR/storage/logs/laravel.log"
echo "  Restart: sudo systemctl restart nginx php8.2-fpm"
echo "  Stop kiosk: pkill -f chromium"
echo ""
echo "============================================"
