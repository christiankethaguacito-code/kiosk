#!/bin/bash
#############################################################
# QUICK UPDATE SCRIPT
# Updates only changed files from USB to Raspberry Pi
# Much faster than full redeploy
#############################################################

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Auto-detect USB location
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="/home/pi/sksu-kiosk"

print_ok() { echo -e "${GREEN}✅ $1${NC}"; }
print_fail() { echo -e "${RED}❌ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }
print_warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }

echo ""
echo "============================================"
echo "   Quick Update from USB"
echo "============================================"
echo ""
echo "USB Source: $SCRIPT_DIR"
echo "Target: $PROJECT_DIR"
echo ""

if [ ! -d "$PROJECT_DIR" ]; then
    print_fail "Project not found at $PROJECT_DIR"
    print_info "Please run deploy-kiosk.sh first"
    exit 1
fi

#############################################################
# STEP 1: Stop kiosk browser (keep services running)
#############################################################
print_info "Stopping kiosk browser..."
pkill -f chromium 2>/dev/null || true
pkill -f chromium-browser 2>/dev/null || true
print_ok "Browser stopped"

#############################################################
# STEP 2: Backup current .env and database
#############################################################
print_info "Backing up .env and database..."
sudo cp "$PROJECT_DIR/.env" "/tmp/kiosk-env-backup" 2>/dev/null || true
sudo cp "$PROJECT_DIR/database/database.sqlite" "/tmp/kiosk-db-backup" 2>/dev/null || true
print_ok "Backup created"

#############################################################
# STEP 3: Update application files (excluding vendor, storage, .env, database)
#############################################################
print_info "Updating application files..."

# Copy app/ directory (controllers, models)
sudo rsync -av --delete "$SCRIPT_DIR/app/" "$PROJECT_DIR/app/"

# Copy resources/ directory (views, js, css)
sudo rsync -av --delete "$SCRIPT_DIR/resources/" "$PROJECT_DIR/resources/"

# Copy routes/
sudo rsync -av --delete "$SCRIPT_DIR/routes/" "$PROJECT_DIR/routes/"

# Copy config/
sudo rsync -av --delete "$SCRIPT_DIR/config/" "$PROJECT_DIR/config/"

# Copy public/ (excluding vendor and storage symlinks)
sudo rsync -av --exclude=vendor --exclude=storage "$SCRIPT_DIR/public/" "$PROJECT_DIR/public/"

# Copy database migrations (but not the actual database file)
sudo rsync -av --exclude=database.sqlite "$SCRIPT_DIR/database/" "$PROJECT_DIR/database/"

# Copy root files (composer.json, package.json, etc.)
sudo cp "$SCRIPT_DIR/composer.json" "$PROJECT_DIR/" 2>/dev/null || true
sudo cp "$SCRIPT_DIR/package.json" "$PROJECT_DIR/" 2>/dev/null || true
sudo cp "$SCRIPT_DIR/artisan" "$PROJECT_DIR/" 2>/dev/null || true

print_ok "Files updated"

#############################################################
# STEP 4: Restore .env and database
#############################################################
print_info "Restoring .env and database..."
sudo cp "/tmp/kiosk-env-backup" "$PROJECT_DIR/.env" 2>/dev/null || true
sudo cp "/tmp/kiosk-db-backup" "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || true
print_ok "Restored"

#############################################################
# STEP 5: Fix case-sensitive folder names
#############################################################
print_info "Fixing case-sensitive folders..."
cd "$PROJECT_DIR/resources/views"

if [ -d "Layouts" ]; then
    sudo mv Layouts layouts_temp
    sudo mv layouts_temp layouts
    print_ok "Fixed: Layouts → layouts"
fi

#############################################################
# STEP 6: Fix permissions
#############################################################
print_info "Fixing permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
sudo chmod 664 "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || true
print_ok "Permissions fixed"

#############################################################
# STEP 7: Check if composer.json changed - update dependencies
#############################################################
print_info "Checking if dependencies need update..."

if [ -f "/tmp/kiosk-composer-backup" ]; then
    if ! diff -q "$PROJECT_DIR/composer.json" "/tmp/kiosk-composer-backup" > /dev/null 2>&1; then
        print_warn "composer.json changed - updating dependencies..."
        cd "$PROJECT_DIR"
        sudo -u www-data composer install --no-dev --optimize-autoloader --no-cache
        print_ok "Dependencies updated"
    else
        print_info "Dependencies unchanged - skipping composer install"
    fi
else
    # First time - save for next comparison
    sudo cp "$PROJECT_DIR/composer.json" "/tmp/kiosk-composer-backup" 2>/dev/null || true
fi

#############################################################
# STEP 8: Run database migrations (only new ones)
#############################################################
print_info "Checking for new migrations..."
cd "$PROJECT_DIR"
MIGRATE_OUTPUT=$(sudo -u www-data php artisan migrate --force 2>&1)

if echo "$MIGRATE_OUTPUT" | grep -q "Nothing to migrate"; then
    print_info "No new migrations"
elif echo "$MIGRATE_OUTPUT" | grep -q "Migrated"; then
    print_ok "New migrations applied"
else
    print_warn "Migration check: $MIGRATE_OUTPUT"
fi

#############################################################
# STEP 9: Clear all Laravel caches
#############################################################
print_info "Clearing caches..."
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear
print_ok "Caches cleared"

#############################################################
# STEP 10: Restart services
#############################################################
print_info "Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sleep 2

if systemctl is-active --quiet php8.2-fpm && systemctl is-active --quiet nginx; then
    print_ok "Services restarted"
else
    print_fail "Service restart failed"
    sudo systemctl status php8.2-fpm
    sudo systemctl status nginx
    exit 1
fi

#############################################################
# STEP 11: Test the system
#############################################################
print_info "Testing system..."
sleep 1
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/)

if [ "$HTTP_CODE" = "200" ]; then
    print_ok "System responding (HTTP $HTTP_CODE)"
else
    print_warn "System returned HTTP $HTTP_CODE"
fi

#############################################################
# STEP 12: Restart kiosk browser
#############################################################
print_info "Restarting kiosk..."

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
        --app=http://localhost/ &
    print_ok "Kiosk browser started"
fi

echo ""
echo "============================================"
echo "   ✅ Update Complete!"
echo "============================================"
echo ""
echo "Updated:"
echo "  • Application code (app/, resources/, routes/)"
echo "  • Views and layouts"
echo "  • Public assets"
echo "  • Configuration files"
echo ""
echo "Preserved:"
echo "  • Database (.env and database.sqlite)"
echo "  • Storage files"
echo "  • Vendor directory (unless composer.json changed)"
echo ""
echo "Test the system now!"
echo "============================================"
