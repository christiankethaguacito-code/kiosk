#!/bin/bash
#############################################################
# ONE-CLICK FIX - Update & Verify System
# Combines update + check into one smooth operation
#############################################################

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="/home/pi/sksu-kiosk"

print_ok() { echo -e "${GREEN}✅ $1${NC}"; }
print_fail() { echo -e "${RED}❌ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }
print_warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }

echo ""
echo "============================================"
echo "   ONE-CLICK FIX: Update & Verify"
echo "============================================"
echo ""

#############################################################
# PHASE 1: STOP SERVICES
#############################################################
print_info "Stopping kiosk browser..."
pkill -f chromium 2>/dev/null || true
sleep 1
print_ok "Browser stopped"

#############################################################
# PHASE 2: BACKUP CRITICAL FILES
#############################################################
print_info "Backing up database and config..."
sudo cp "$PROJECT_DIR/.env" "/tmp/kiosk-env-backup" 2>/dev/null || true
sudo cp "$PROJECT_DIR/database/database.sqlite" "/tmp/kiosk-db-backup" 2>/dev/null || true
print_ok "Backup complete"

#############################################################
# PHASE 3: UPDATE FILES FROM USB
#############################################################
print_info "Updating files from USB..."

# Update application code
sudo rsync -av "$SCRIPT_DIR/app/" "$PROJECT_DIR/app/" > /dev/null 2>&1
sudo rsync -av "$SCRIPT_DIR/resources/" "$PROJECT_DIR/resources/" > /dev/null 2>&1
sudo rsync -av "$SCRIPT_DIR/routes/" "$PROJECT_DIR/routes/" > /dev/null 2>&1
sudo rsync -av "$SCRIPT_DIR/config/" "$PROJECT_DIR/config/" > /dev/null 2>&1
sudo rsync -av --exclude=vendor --exclude=storage "$SCRIPT_DIR/public/" "$PROJECT_DIR/public/" > /dev/null 2>&1

# Copy root files
sudo cp "$SCRIPT_DIR/composer.json" "$PROJECT_DIR/" 2>/dev/null || true
sudo cp "$SCRIPT_DIR/artisan" "$PROJECT_DIR/" 2>/dev/null || true

print_ok "Files updated"

#############################################################
# PHASE 4: RESTORE DATABASE AND CONFIG
#############################################################
print_info "Restoring database and config..."
sudo cp "/tmp/kiosk-env-backup" "$PROJECT_DIR/.env" 2>/dev/null || true
sudo cp "/tmp/kiosk-db-backup" "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || true
print_ok "Restored"

#############################################################
# PHASE 5: FIX CASE-SENSITIVE FOLDERS
#############################################################
print_info "Fixing case-sensitive folders..."
cd "$PROJECT_DIR/resources/views"

if [ -d "Layouts" ]; then
    sudo mv Layouts layouts_temp 2>/dev/null || true
    sudo mv layouts_temp layouts 2>/dev/null || true
    print_ok "Fixed: Layouts → layouts"
elif [ -d "layouts" ]; then
    print_ok "Folder already lowercase"
fi

#############################################################
# PHASE 6: FIX PERMISSIONS
#############################################################
print_info "Fixing permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage" 2>/dev/null || true
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache" 2>/dev/null || true
sudo chmod 664 "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || true
print_ok "Permissions fixed"

#############################################################
# PHASE 7: CONFIGURE ENVIRONMENT
#############################################################
print_info "Configuring environment..."
cd "$PROJECT_DIR"

if grep -q "DB_CONNECTION=mysql" .env 2>/dev/null; then
    sudo sed -i.bak 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
fi

if ! grep -q "DB_CONNECTION=sqlite" .env 2>/dev/null; then
    echo "DB_CONNECTION=sqlite" | sudo tee -a .env > /dev/null
fi

print_ok "Environment configured"

#############################################################
# PHASE 8: CLEAR ALL CACHES
#############################################################
print_info "Clearing all caches..."
cd "$PROJECT_DIR"

sudo -u www-data php artisan config:clear 2>/dev/null || true
sudo -u www-data php artisan cache:clear 2>/dev/null || true
sudo -u www-data php artisan view:clear 2>/dev/null || true
sudo -u www-data php artisan route:clear 2>/dev/null || true

# Clear compiled views manually
sudo rm -rf "$PROJECT_DIR/storage/framework/views/"*.php 2>/dev/null || true

print_ok "Caches cleared"

#############################################################
# PHASE 9: RESTART SERVICES
#############################################################
print_info "Restarting PHP-FPM..."
sudo systemctl restart php8.2-fpm
sleep 2

if systemctl is-active --quiet php8.2-fpm; then
    print_ok "PHP-FPM running"
else
    print_fail "PHP-FPM failed!"
    exit 1
fi

print_info "Restarting Nginx..."
sudo systemctl restart nginx
sleep 2

if systemctl is-active --quiet nginx; then
    print_ok "Nginx running"
else
    print_fail "Nginx failed!"
    exit 1
fi

#############################################################
# PHASE 10: TEST SYSTEM
#############################################################
print_info "Testing system..."
sleep 2

# Test homepage
HOME_CODE=$(curl -s -o /tmp/test.html -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")

if [ "$HOME_CODE" = "200" ]; then
    print_ok "Homepage: HTTP 200 OK"
    
    # Check if it's HTML
    if head -1 /tmp/test.html | grep -qi "<!DOCTYPE\|<html"; then
        print_ok "Response is valid HTML"
    else
        print_warn "Response might not be HTML"
        echo "First 200 chars:"
        head -c 200 /tmp/test.html
    fi
else
    print_warn "Homepage: HTTP $HOME_CODE"
fi

# Test login page
LOGIN_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/login 2>/dev/null || echo "000")
if [ "$LOGIN_CODE" = "200" ]; then
    print_ok "Login page: HTTP 200 OK"
else
    print_warn "Login page: HTTP $LOGIN_CODE"
fi

#############################################################
# PHASE 11: START KIOSK
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
        --app=http://localhost/ > /dev/null 2>&1 &
    
    sleep 2
    if pgrep -f chromium > /dev/null; then
        print_ok "Kiosk browser started"
    fi
fi

#############################################################
# SUMMARY
#############################################################
echo ""
echo "============================================"
echo "   ✅ Update Complete!"
echo "============================================"
echo ""
echo "Status:"
echo "  • Services: $(systemctl is-active php8.2-fpm) / $(systemctl is-active nginx)"
echo "  • Homepage: HTTP $HOME_CODE"
echo "  • Login: HTTP $LOGIN_CODE"
echo ""

if [ "$HOME_CODE" = "200" ] && [ "$LOGIN_CODE" = "200" ]; then
    echo "🎉 Everything is working perfectly!"
else
    echo "⚠️  Some issues detected"
    echo ""
    echo "Check logs:"
    echo "  tail -f $PROJECT_DIR/storage/logs/laravel.log"
    echo "  sudo tail -f /var/log/nginx/error.log"
fi

echo ""
echo "============================================"
