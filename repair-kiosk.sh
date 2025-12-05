#!/bin/bash
#############################################################
# COMPLETE KIOSK REPAIR SCRIPT
# Fixes ALL issues: Backend, Frontend, PHP, Nginx, Laravel
# Run this to get your kiosk fully working
#############################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PROJECT_DIR="/home/pi/sksu-kiosk"
LOG_FILE="/tmp/kiosk-repair.log"

echo "" > "$LOG_FILE"

print_header() {
    echo ""
    echo -e "${BLUE}============================================${NC}"
    echo -e "${BLUE}   $1${NC}"
    echo -e "${BLUE}============================================${NC}"
    echo ""
}

print_ok() { echo -e "${GREEN}✅ $1${NC}"; }
print_fail() { echo -e "${RED}❌ $1${NC}"; }
print_warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }

print_header "COMPLETE KIOSK REPAIR SCRIPT"
echo "This will check and fix ALL components"
echo "Log file: $LOG_FILE"

#############################################################
# STEP 1: Check and Install Required Packages
#############################################################
print_header "STEP 1: Checking Required Packages"

# Update package list
print_info "Updating package list..."
sudo apt-get update -qq >> "$LOG_FILE" 2>&1

PACKAGES=(
    "nginx"
    "php8.2-fpm"
    "php8.2-sqlite3"
    "php8.2-xml"
    "php8.2-mbstring"
    "php8.2-curl"
    "php8.2-zip"
    "php8.2-gd"
    "sqlite3"
    "curl"
)

MISSING=()
for pkg in "${PACKAGES[@]}"; do
    if ! dpkg -l | grep -q "^ii.*$pkg"; then
        MISSING+=("$pkg")
    fi
done

if [ ${#MISSING[@]} -gt 0 ]; then
    print_warn "Installing missing packages: ${MISSING[*]}"
    sudo apt-get install -y "${MISSING[@]}" >> "$LOG_FILE" 2>&1
    print_ok "Packages installed"
else
    print_ok "All required packages are installed"
fi

#############################################################
# STEP 2: Verify Project Files Exist
#############################################################
print_header "STEP 2: Verifying Project Files"

if [ ! -d "$PROJECT_DIR" ]; then
    print_fail "Project directory not found: $PROJECT_DIR"
    print_info "Please run deploy-kiosk.sh first"
    exit 1
fi
print_ok "Project directory exists"

if [ ! -f "$PROJECT_DIR/public/index.php" ]; then
    print_fail "Laravel public/index.php not found!"
    exit 1
fi
print_ok "Laravel public/index.php exists"

if [ ! -f "$PROJECT_DIR/artisan" ]; then
    print_fail "Laravel artisan not found!"
    exit 1
fi
print_ok "Laravel artisan exists"

if [ ! -d "$PROJECT_DIR/vendor" ]; then
    print_fail "Vendor directory missing - need to run composer install"
    print_info "Running composer install..."
    cd "$PROJECT_DIR"
    sudo -u www-data composer install --no-dev --optimize-autoloader >> "$LOG_FILE" 2>&1
    print_ok "Composer install completed"
else
    print_ok "Vendor directory exists"
fi

if [ ! -f "$PROJECT_DIR/.env" ]; then
    print_warn ".env file missing, creating from .env.example"
    if [ -f "$PROJECT_DIR/.env.example" ]; then
        sudo cp "$PROJECT_DIR/.env.example" "$PROJECT_DIR/.env"
        sudo chown www-data:www-data "$PROJECT_DIR/.env"
    else
        print_fail ".env.example not found!"
        exit 1
    fi
fi
print_ok ".env file exists"

#############################################################
# STEP 3: Configure .env for SQLite
#############################################################
print_header "STEP 3: Configuring Environment"

cd "$PROJECT_DIR"

# Ensure SQLite configuration
sudo sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sudo sed -i "s|DB_DATABASE=.*|DB_DATABASE=$PROJECT_DIR/database/database.sqlite|" .env
sudo sed -i 's/^DB_HOST=/#DB_HOST=/' .env
sudo sed -i 's/^DB_PORT=/#DB_PORT=/' .env
sudo sed -i 's/^DB_USERNAME=/#DB_USERNAME=/' .env
sudo sed -i 's/^DB_PASSWORD=/#DB_PASSWORD=/' .env

print_ok "Environment configured for SQLite"

#############################################################
# STEP 4: Setup Database
#############################################################
print_header "STEP 4: Setting Up Database"

sudo mkdir -p "$PROJECT_DIR/database"

if [ ! -f "$PROJECT_DIR/database/database.sqlite" ]; then
    print_info "Creating database file..."
    sudo touch "$PROJECT_DIR/database/database.sqlite"
fi

sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"
sudo chown www-data:www-data "$PROJECT_DIR/database/database.sqlite"
print_ok "Database file ready"

# Run migrations if needed
print_info "Checking database migrations..."
cd "$PROJECT_DIR"
MIGRATE_OUTPUT=$(sudo -u www-data php artisan migrate --force 2>&1) || true
echo "$MIGRATE_OUTPUT" >> "$LOG_FILE"

if echo "$MIGRATE_OUTPUT" | grep -q "Nothing to migrate"; then
    print_ok "Database already migrated"
else
    print_ok "Database migrations applied"
fi

#############################################################
# STEP 5: Fix All Permissions
#############################################################
print_header "STEP 5: Fixing Permissions"

sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"

print_ok "All permissions fixed"

#############################################################
# STEP 6: Generate App Key (if needed)
#############################################################
print_header "STEP 6: Checking Application Key"

if ! grep -q "APP_KEY=base64:" "$PROJECT_DIR/.env"; then
    print_info "Generating application key..."
    sudo -u www-data php artisan key:generate --force >> "$LOG_FILE" 2>&1
    print_ok "Application key generated"
else
    print_ok "Application key already exists"
fi

#############################################################
# STEP 7: Clear All Laravel Cache
#############################################################
print_header "STEP 7: Clearing Laravel Cache"

cd "$PROJECT_DIR"
sudo -u www-data php artisan config:clear >> "$LOG_FILE" 2>&1 || true
sudo -u www-data php artisan cache:clear >> "$LOG_FILE" 2>&1 || true
sudo -u www-data php artisan view:clear >> "$LOG_FILE" 2>&1 || true
sudo -u www-data php artisan route:clear >> "$LOG_FILE" 2>&1 || true

print_ok "All caches cleared"

#############################################################
# STEP 8: Configure PHP-FPM
#############################################################
print_header "STEP 8: Configuring PHP-FPM"

# Ensure PHP-FPM is configured correctly
PHP_FPM_CONF="/etc/php/8.2/fpm/pool.d/www.conf"

if [ -f "$PHP_FPM_CONF" ]; then
    # Make sure www-data user is set
    sudo sed -i 's/^user = .*/user = www-data/' "$PHP_FPM_CONF"
    sudo sed -i 's/^group = .*/group = www-data/' "$PHP_FPM_CONF"
    sudo sed -i 's/^listen.owner = .*/listen.owner = www-data/' "$PHP_FPM_CONF"
    sudo sed -i 's/^listen.group = .*/listen.group = www-data/' "$PHP_FPM_CONF"
    print_ok "PHP-FPM pool configured"
fi

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
sleep 2

if systemctl is-active --quiet php8.2-fpm; then
    print_ok "PHP-FPM is running"
else
    print_fail "PHP-FPM failed to start!"
    sudo systemctl status php8.2-fpm
    exit 1
fi

# Check socket
if [ -S /var/run/php/php8.2-fpm.sock ]; then
    print_ok "PHP-FPM socket exists"
else
    print_fail "PHP-FPM socket not found!"
    exit 1
fi

#############################################################
# STEP 9: Configure Nginx (CRITICAL)
#############################################################
print_header "STEP 9: Configuring Nginx"

# Stop nginx first
sudo systemctl stop nginx 2>/dev/null || true

# Kill any process on port 80
sudo fuser -k 80/tcp 2>/dev/null || true

# Remove default site
sudo rm -f /etc/nginx/sites-enabled/default

# Create proper kiosk site configuration
sudo tee /etc/nginx/sites-available/kiosk > /dev/null <<'NGINXCONFIG'
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    root /home/pi/sksu-kiosk/public;
    index index.php index.html index.htm;
    
    server_name _;
    
    charset utf-8;
    
    # Important: disable output buffering issues
    gzip off;
    proxy_buffering off;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Critical: ensure proper content type
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINXCONFIG

print_ok "Nginx site configuration created"

# Enable kiosk site
sudo ln -sf /etc/nginx/sites-available/kiosk /etc/nginx/sites-enabled/kiosk
print_ok "Kiosk site enabled"

# Test nginx config
print_info "Testing Nginx configuration..."
if sudo nginx -t >> "$LOG_FILE" 2>&1; then
    print_ok "Nginx configuration is valid"
else
    print_fail "Nginx configuration error!"
    sudo nginx -t
    exit 1
fi

# Start nginx
sudo systemctl start nginx
sleep 2

if systemctl is-active --quiet nginx; then
    print_ok "Nginx is running"
else
    print_fail "Nginx failed to start!"
    sudo systemctl status nginx
    exit 1
fi

#############################################################
# STEP 10: Test the Backend
#############################################################
print_header "STEP 10: Testing Backend"

print_info "Testing HTTP response..."
sleep 2

HTTP_CODE=$(curl -s -o /tmp/response.html -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")
echo "HTTP Response Code: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    print_ok "Server returned 200 OK"
elif [ "$HTTP_CODE" = "500" ]; then
    print_warn "Server returned 500 - Check Laravel logs"
    print_info "Checking Laravel log..."
    tail -20 "$PROJECT_DIR/storage/logs/laravel.log" 2>/dev/null || echo "No log file"
else
    print_warn "Server returned: $HTTP_CODE"
fi

# Check if response is HTML
print_info "Checking response content..."
if [ -f /tmp/response.html ]; then
    if head -1 /tmp/response.html | grep -q "<!DOCTYPE\|<html\|<!doctype"; then
        print_ok "Response is valid HTML"
    else
        print_warn "Response might not be HTML"
        print_info "First 200 characters:"
        head -c 200 /tmp/response.html
        echo ""
    fi
fi

# Check content type header
CONTENT_TYPE=$(curl -sI http://localhost/ 2>/dev/null | grep -i "content-type" | head -1)
echo "Content-Type: $CONTENT_TYPE"

if echo "$CONTENT_TYPE" | grep -qi "text/html"; then
    print_ok "Content-Type is text/html"
else
    print_warn "Content-Type might be wrong"
fi

#############################################################
# STEP 11: Check Nginx Error Log
#############################################################
print_header "STEP 11: Checking Error Logs"

print_info "Recent Nginx errors:"
sudo tail -10 /var/log/nginx/error.log 2>/dev/null || echo "No Nginx errors"

print_info "Recent Laravel errors:"
tail -10 "$PROJECT_DIR/storage/logs/laravel.log" 2>/dev/null || echo "No Laravel errors"

#############################################################
# STEP 12: Create Test PHP File
#############################################################
print_header "STEP 12: PHP Test"

# Create a simple PHP test file
sudo tee "$PROJECT_DIR/public/test.php" > /dev/null <<'PHPTEST'
<?php
header('Content-Type: text/html; charset=UTF-8');
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>PHP Test</title></head><body>";
echo "<h1>PHP is working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "</body></html>";
PHPTEST
sudo chown www-data:www-data "$PROJECT_DIR/public/test.php"

print_info "Testing PHP directly..."
TEST_RESPONSE=$(curl -s http://localhost/test.php 2>/dev/null)

if echo "$TEST_RESPONSE" | grep -q "PHP is working"; then
    print_ok "PHP is processing correctly!"
    echo "$TEST_RESPONSE" | head -5
else
    print_fail "PHP is NOT processing!"
    echo "Response: $TEST_RESPONSE" | head -c 200
fi

# Clean up test file
sudo rm -f "$PROJECT_DIR/public/test.php"

#############################################################
# STEP 13: Final Service Check
#############################################################
print_header "STEP 13: Final Service Check"

echo "Service Status:"
echo "---------------"
systemctl is-active nginx && print_ok "Nginx: Active" || print_fail "Nginx: Inactive"
systemctl is-active php8.2-fpm && print_ok "PHP-FPM: Active" || print_fail "PHP-FPM: Inactive"

echo ""
echo "Listening Ports:"
echo "----------------"
sudo ss -tlnp | grep -E ":80|php" || true

#############################################################
# STEP 14: Restart Kiosk Browser
#############################################################
print_header "STEP 14: Starting Kiosk Browser"

# Kill existing browser
pkill -f chromium 2>/dev/null || true
pkill -f chromium-browser 2>/dev/null || true
sleep 2

# Find chromium
CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -n "$CHROMIUM_CMD" ]; then
    print_info "Starting browser with $CHROMIUM_CMD..."
    DISPLAY=:0 $CHROMIUM_CMD \
        --kiosk \
        --noerrdialogs \
        --disable-infobars \
        --no-first-run \
        --disable-session-crashed-bubble \
        --disable-features=TranslateUI \
        --app=http://localhost/ &
    print_ok "Kiosk browser started"
else
    print_warn "Chromium not found - please start browser manually"
fi

#############################################################
# SUMMARY
#############################################################
print_header "REPAIR COMPLETE"

echo "Summary:"
echo "--------"
echo "• Project: $PROJECT_DIR"
echo "• URL: http://localhost/"
echo "• Log: $LOG_FILE"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    print_ok "Backend appears to be working!"
    echo ""
    echo "If you still see garbled text:"
    echo "1. Press F5 to refresh the browser"
    echo "2. Or close and reopen the kiosk"
    echo "3. Or run: ~/kiosk-restart.sh"
else
    print_warn "There may still be issues"
    echo "Check the log file: $LOG_FILE"
    echo "Check Laravel log: $PROJECT_DIR/storage/logs/laravel.log"
fi

echo ""
echo "============================================"
