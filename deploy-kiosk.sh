#!/bin/bash
#############################################################
# SKSU Campus Kiosk - Plug & Play Deployment Script
# For Raspberry Pi 5 with Linux and Chromium
# Author: Christian Keth Aguacito
# Date: December 2, 2025
# Version: 2.0 - Auto-detection and bulletproof services
#############################################################

# Parse command line arguments
SKIP_DEPS=false
for arg in "$@"; do
    case $arg in
        --skip-deps)
            SKIP_DEPS=true
            shift
            ;;
        --help|-h)
            echo "Usage: $0 [OPTIONS]"
            echo "Options:"
            echo "  --skip-deps    Skip system package and composer dependency installation"
            echo "  --help, -h     Show this help message"
            exit 0
            ;;
    esac
done

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Auto-detect script directory (USB drive mount point)
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="/home/pi/sksu-kiosk"

# Detect user home directory (works for any user, not just pi)
USER_HOME="${HOME:-/home/pi}"
CURRENT_USER="${USER:-pi}"

# Save logs to USB (persistent across unplugs)
LOG_FILE="$SCRIPT_DIR/deployment.log"
ERROR_LOG="$SCRIPT_DIR/deployment-errors.log"
FULL_LOG="$SCRIPT_DIR/deployment-full.log"

# Also save to home directory for easy access
HOME_LOG="$USER_HOME/kiosk-installation.log"
HOME_ERROR_LOG="$USER_HOME/kiosk-errors.log"

# Initialize logs (save everything to USB)
{
    echo "============================================================"
    echo "   SKSU Campus Kiosk - Full Deployment Log"
    echo "   Started: $(date)"
    echo "   USB Location: $SCRIPT_DIR"
    echo "============================================================"
    echo ""
} | tee "$LOG_FILE" "$FULL_LOG" > /dev/null

{
    echo "============================================================"
    echo "   SKSU Campus Kiosk - Error Log"
    echo "   Started: $(date)"
    echo "============================================================"
    echo ""
} > "$ERROR_LOG"

# Function to print colored messages (logs everything to USB)
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a "$LOG_FILE" "$FULL_LOG"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE" "$FULL_LOG"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE" "$FULL_LOG"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] WARNING: $1" | tee -a "$ERROR_LOG"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE" "$FULL_LOG"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1" | tee -a "$ERROR_LOG"
}

# Function to log command errors (saves to both USB and captures full output)
log_command_error() {
    local command="$1"
    local error_output="$2"
    
    {
        echo ""
        echo "-----------------------------------------------------------"
        echo "Command: $command"
        echo "Time: $(date '+%Y-%m-%d %H:%M:%S')"
        echo "Error Output:"
        echo "$error_output"
        echo "-----------------------------------------------------------"
        echo ""
    } | tee -a "$ERROR_LOG" "$FULL_LOG" > /dev/null
}

# Start deployment (log to both screen and USB)
{
    echo "============================================================"
    echo "   SKSU Campus Kiosk - Automatic Deployment"
    echo "   Raspberry Pi 5 Plug & Play Installation"
    echo "============================================================"
    echo ""
} | tee -a "$FULL_LOG"

print_info "Starting deployment at $(date)"

# Step 1: Check if running on Raspberry Pi
print_info "Checking system compatibility..."
if [ ! -f /proc/device-tree/model ] || ! grep -q "Raspberry Pi" /proc/device-tree/model; then
    print_warning "This script is designed for Raspberry Pi. Continuing anyway..."
else
    print_success "Raspberry Pi detected"
fi

# Step 2: Update system
if [ "$SKIP_DEPS" = false ]; then
    print_info "Updating system packages..."
    sudo apt-get update -y 2>&1 | tee -a "$FULL_LOG" > /dev/null
    print_success "System packages updated"
else
    print_info "Skipping system update (--skip-deps flag set)"
fi

# Step 3: Install required packages
if [ "$SKIP_DEPS" = false ]; then
    print_info "Checking for required packages..."
    
    # Check which packages are missing
    MISSING_PACKAGES=()
    REQUIRED_PACKAGES=("php8.2" "php8.2-fpm" "php8.2-sqlite3" "php8.2-xml" "php8.2-mbstring" "php8.2-curl" "php8.2-zip" "php8.2-gd" "nginx" "sqlite3" "git" "curl" "unzip" "chromium" "x11-xserver-utils" "unclutter" "sed" "fonts-liberation" "fonts-noto" "fonts-noto-cjk" "fonts-noto-color-emoji" "ttf-mscorefonts-installer")
    
    for pkg in "${REQUIRED_PACKAGES[@]}"; do
        if ! dpkg -l | grep -q "^ii.*$pkg"; then
            MISSING_PACKAGES+=("$pkg")
        fi
    done
    
    if [ ${#MISSING_PACKAGES[@]} -eq 0 ]; then
        print_success "All required packages already installed"
    else
        print_info "Installing missing packages: ${MISSING_PACKAGES[*]}"
        INSTALL_OUTPUT=$(sudo apt-get install -y "${MISSING_PACKAGES[@]}" 2>&1)
        
        # Check if install was successful
        if [ $? -ne 0 ]; then
            print_error "Package installation failed!"
            log_command_error "apt-get install packages" "$INSTALL_OUTPUT"
            exit 1
        fi
        
        # Log package install output to USB
        echo "$INSTALL_OUTPUT" | tee -a "$FULL_LOG" > /dev/null
        print_success "Required packages installed"
    fi
else
    print_info "Skipping package installation (--skip-deps flag set)"
fi

# Ensure services exist before continuing
if ! systemctl list-unit-files | grep -q php8.2-fpm; then
    print_error "PHP-FPM not installed properly!"
    log_command_error "PHP-FPM installation check" "Service not found in systemctl list-unit-files"
    print_error "Run: sudo apt-get install -y php8.2-fpm"
    exit 1
fi

if ! systemctl list-unit-files | grep -q nginx; then
    print_error "Nginx not installed properly!"
    log_command_error "Nginx installation check" "Service not found in systemctl list-unit-files"
    print_error "Run: sudo apt-get install -y nginx"
    exit 1
fi

# Verify nginx directories exist
if [ ! -d "/etc/nginx/sites-available" ] || [ ! -d "/etc/nginx/sites-enabled" ]; then
    print_error "Nginx directories missing!"
    print_error "Nginx may not be installed correctly"
    exit 1
fi

print_success "Required packages installed"

# Step 4: Install Composer
if [ "$SKIP_DEPS" = false ]; then
    print_info "Checking Composer installation..."
    if [ ! -f /usr/local/bin/composer ]; then
        print_info "Installing Composer..."
        COMPOSER_OUTPUT=$(curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer 2>&1)
        if [ $? -eq 0 ]; then
            print_success "Composer installed"
        else
            print_error "Composer installation failed"
            log_command_error "Composer installation" "$COMPOSER_OUTPUT"
            exit 1
        fi
        echo "$COMPOSER_OUTPUT" | tee -a "$FULL_LOG" > /dev/null
    else
        print_success "Composer already installed"
    fi
else
    print_info "Skipping Composer installation check (--skip-deps flag set)"
fi

# Step 5: Copy project files
print_info "Copying project files to $PROJECT_DIR..."
sudo mkdir -p "$PROJECT_DIR"

# Copy all files including hidden files (.env.example, .gitignore, etc.)
print_info "Copying visible files..."
sudo cp -r "$SCRIPT_DIR"/* "$PROJECT_DIR/" 2>/dev/null || true

# Copy hidden files (files starting with .)
print_info "Copying hidden files (.env.example, etc.)..."
sudo cp -r "$SCRIPT_DIR"/.* "$PROJECT_DIR/" 2>/dev/null || true

sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
print_success "Project files copied (including hidden files)"

# Step 6: Setup SQLite database
print_info "Setting up SQLite database..."
sudo mkdir -p "$PROJECT_DIR/database"

if [ -f "$SCRIPT_DIR/database/database.sqlite" ]; then
    print_info "Copying existing database..."
    sudo cp "$SCRIPT_DIR/database/database.sqlite" "$PROJECT_DIR/database/database.sqlite"
    print_success "Database file copied"
else
    print_info "Creating new database file..."
    sudo touch "$PROJECT_DIR/database/database.sqlite"
    print_success "New database file created"
fi

# Set database permissions
sudo chmod 664 "$PROJECT_DIR/database/database.sqlite"
sudo chown www-data:www-data "$PROJECT_DIR/database/database.sqlite"
print_success "SQLite database configured"

# Step 7: Configure Laravel environment
print_info "Configuring Laravel environment..."
cd "$PROJECT_DIR"

# Check if .env.example exists in source
if [ ! -f "$SCRIPT_DIR/.env.example" ]; then
    print_error ".env.example not found in $SCRIPT_DIR!"
    print_error "Make sure .env.example is in the Navi folder on USB"
    exit 1
fi

if [ ! -f .env ]; then
    sudo cp "$SCRIPT_DIR/.env.example" .env
    print_info "Created .env from .env.example"
fi

# Update .env file for SQLite
sudo sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sudo sed -i "s|DB_DATABASE=.*|DB_DATABASE=$PROJECT_DIR/database/database.sqlite|" .env
sudo sed -i 's/^DB_HOST=/#DB_HOST=/' .env
sudo sed -i 's/^DB_PORT=/#DB_PORT=/' .env
sudo sed -i 's/^DB_USERNAME=/#DB_USERNAME=/' .env
sudo sed -i 's/^DB_PASSWORD=/#DB_PASSWORD=/' .env
sudo sed -i 's/APP_URL=.*/APP_URL=http:\/\/localhost/' .env
sudo sed -i 's/SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env
sudo sed -i 's/APP_CHARSET=.*/APP_CHARSET=utf-8/' .env
sudo sed -i 's/APP_LC_COLLATE=.*/APP_LC_COLLATE=utf8_unicode_ci/' .env

print_success "Environment configured for SQLite"

# Step 8: Set proper permissions before composer install
print_info "Setting proper file permissions..."
cd "$PROJECT_DIR"
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"

sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
print_success "Permissions set"

# Step 9: Install Laravel dependencies
if [ "$SKIP_DEPS" = false ]; then
    # Check if vendor directory already exists and has packages
    if [ -d "$PROJECT_DIR/vendor" ] && [ "$(ls -A "$PROJECT_DIR/vendor" 2>/dev/null)" ]; then
        print_info "Checking if composer dependencies are up to date..."
        
        # Quick check if vendor is populated
        if [ -f "$PROJECT_DIR/vendor/autoload.php" ]; then
            print_success "Composer dependencies already installed"
            print_info "Run 'composer update' manually if you need to update packages"
        else
            print_warning "vendor/ exists but seems incomplete, reinstalling..."
            sudo rm -rf "$PROJECT_DIR/vendor"
        fi
    fi
    
    # Install only if vendor doesn't exist or was incomplete
    if [ ! -f "$PROJECT_DIR/vendor/autoload.php" ]; then
        print_info "Installing Laravel dependencies..."
        print_info "This may take a few minutes..."
        
        # Ensure we're in the correct directory
        if [ ! -f "$PROJECT_DIR/composer.json" ]; then
            print_error "composer.json not found in $PROJECT_DIR!"
            print_error "Current directory: $(pwd)"
            print_error "Project files may not have been copied correctly"
            exit 1
        fi
        
        COMPOSER_INSTALL_OUTPUT=$(sudo -u www-data composer install --no-dev --optimize-autoloader --working-dir="$PROJECT_DIR" 2>&1)
        if [ $? -ne 0 ]; then
            print_error "Composer install failed!"
            log_command_error "composer install" "$COMPOSER_INSTALL_OUTPUT"
            echo "$COMPOSER_INSTALL_OUTPUT" | tee -a "$FULL_LOG"
            
            # Try alternative approach
            print_warning "Retrying with different permissions..."
            sudo chown -R www-data:www-data "$PROJECT_DIR"
            
            COMPOSER_INSTALL_OUTPUT=$(cd "$PROJECT_DIR" && sudo -u www-data composer install --no-dev --optimize-autoloader 2>&1)
            if [ $? -ne 0 ]; then
                print_error "Composer install still failed!"
                log_command_error "composer install (retry)" "$COMPOSER_INSTALL_OUTPUT"
                echo "$COMPOSER_INSTALL_OUTPUT" | tee -a "$FULL_LOG"
                print_error "Check $FULL_LOG for details"
                exit 1
            fi
        fi
        echo "$COMPOSER_INSTALL_OUTPUT" | tee -a "$FULL_LOG" > /dev/null
        print_success "Laravel dependencies installed"
    fi
else
    print_info "Skipping composer install (--skip-deps flag set)"
    if [ ! -f "$PROJECT_DIR/vendor/autoload.php" ]; then
        print_error "vendor/ directory missing but --skip-deps is set!"
        print_error "Run without --skip-deps first, or manually run: composer install"
        exit 1
    fi
    print_success "Using existing vendor/ directory"
fi

# Step 10: Generate application key (if not exists)
print_info "Checking application key..."
if ! grep -q "APP_KEY=base64:" "$PROJECT_DIR/.env"; then
    KEY_GEN_OUTPUT=$(sudo -u www-data php artisan key:generate --force 2>&1)
    if [ $? -eq 0 ]; then
        print_success "Application key generated"
    else
        print_error "Key generation failed"
        log_command_error "php artisan key:generate" "$KEY_GEN_OUTPUT"
    fi
    echo "$KEY_GEN_OUTPUT" >> "$LOG_FILE"
else
    print_info "Application key already exists"
fi

# Step 11: Run database migrations (if database is empty or new)
print_info "Checking database and running migrations..."
DB_SIZE=$(stat -f%z "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || stat -c%s "$PROJECT_DIR/database/database.sqlite" 2>/dev/null || echo "0")
if [ "$DB_SIZE" -lt 1024 ]; then
    print_info "Database is empty, running migrations and seeders..."
    MIGRATE_OUTPUT=$(sudo -u www-data php artisan migrate --force 2>&1)
    if [ $? -ne 0 ]; then
        print_error "Migration failed"
        log_command_error "php artisan migrate" "$MIGRATE_OUTPUT"
    fi
    echo "$MIGRATE_OUTPUT" >> "$LOG_FILE"
    
    SEED_OUTPUT=$(sudo -u www-data php artisan db:seed --force 2>&1)
    if [ $? -ne 0 ]; then
        print_warning "Database seeding had issues (this may be ok)"
        log_command_error "php artisan db:seed" "$SEED_OUTPUT"
    fi
    echo "$SEED_OUTPUT" >> "$LOG_FILE"
    print_success "Database setup completed"
else
    print_info "Database already contains data, skipping migrations"
    print_warning "If you need to run migrations, do it manually with: php artisan migrate"
fi

# Step 12: Create storage link
print_info "Creating storage symbolic link..."
STORAGE_LINK_OUTPUT=$(sudo -u www-data php artisan storage:link 2>&1)
if [ $? -ne 0 ]; then
    print_warning "Storage link creation had issues (may already exist)"
    log_command_error "php artisan storage:link" "$STORAGE_LINK_OUTPUT"
fi
echo "$STORAGE_LINK_OUTPUT" >> "$LOG_FILE"
print_success "Storage link created"

# Step 13: Set final permissions
print_info "Setting file permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR/storage"
sudo chown -R www-data:www-data "$PROJECT_DIR/bootstrap/cache"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
print_success "Permissions set"

# Step 14: Optimize Laravel for production
print_info "Optimizing Laravel for production..."
sudo -u www-data php artisan config:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
sudo -u www-data php artisan route:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
sudo -u www-data php artisan view:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
print_success "Laravel optimized"

# Step 15: Configure Nginx
print_info "Configuring Nginx..."
sudo tee /etc/nginx/sites-available/kiosk > /dev/null <<EOF
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    root $PROJECT_DIR/public;
    index index.php index.html;
    
    server_name _;
    
    charset utf-8;
    
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PHP_VALUE "default_charset=UTF-8";
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
EOF

sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -sf /etc/nginx/sites-available/kiosk /etc/nginx/sites-enabled/

# Test Nginx configuration
NGINX_TEST_OUTPUT=$(sudo nginx -t 2>&1)
if [ $? -ne 0 ]; then
    print_error "Nginx configuration test failed!"
    log_command_error "nginx -t" "$NGINX_TEST_OUTPUT"
    print_info "Check $ERROR_LOG for details"
    exit 1
fi
echo "$NGINX_TEST_OUTPUT" >> "$LOG_FILE"

# Start and enable PHP-FPM first (must be running before Nginx can use it)
print_info "Starting PHP-FPM service..."
sudo systemctl stop php8.2-fpm 2>/dev/null || true

PHP_START_OUTPUT=$(sudo systemctl start php8.2-fpm 2>&1)
if [ $? -ne 0 ]; then
    print_error "PHP-FPM start command failed!"
    log_command_error "systemctl start php8.2-fpm" "$PHP_START_OUTPUT"
fi

PHP_ENABLE_OUTPUT=$(sudo systemctl enable php8.2-fpm 2>&1)
if [ $? -ne 0 ]; then
    print_warning "PHP-FPM enable had issues (may already be enabled)"
    log_command_error "systemctl enable php8.2-fpm" "$PHP_ENABLE_OUTPUT"
fi

# Verify PHP-FPM is running
sleep 2
if ! systemctl is-active --quiet php8.2-fpm; then
    print_error "PHP-FPM failed to start!"
    PHP_STATUS_OUTPUT=$(sudo systemctl status php8.2-fpm 2>&1)
    log_command_error "PHP-FPM status check" "$PHP_STATUS_OUTPUT"
    echo "$PHP_STATUS_OUTPUT"
    exit 1
fi
print_success "PHP-FPM is running and enabled"

# Check for port 80 conflicts and stop conflicting services
print_info "Checking for port 80 conflicts..."
if sudo lsof -i :80 -t >/dev/null 2>&1 || sudo netstat -tuln | grep -q ":80 "; then
    print_warning "Port 80 is in use - stopping conflicting services..."
    
    # Stop common services that use port 80
    sudo systemctl stop apache2 2>/dev/null || true
    sudo systemctl disable apache2 2>/dev/null || true
    sudo systemctl stop lighttpd 2>/dev/null || true
    sudo systemctl disable lighttpd 2>/dev/null || true
    
    # Kill any remaining processes on port 80
    sudo lsof -ti :80 | xargs -r sudo kill -9 2>/dev/null || true
    
    sleep 2
    print_success "Port 80 cleared"
else
    print_info "Port 80 is available"
fi

# Now start and enable Nginx
print_info "Starting Nginx service..."
sudo systemctl stop nginx 2>/dev/null || true

NGINX_START_OUTPUT=$(sudo systemctl start nginx 2>&1)
if [ $? -ne 0 ]; then
    print_error "Nginx start command failed!"
    log_command_error "systemctl start nginx" "$NGINX_START_OUTPUT"
    
    # Try one more time after killing port 80 processes
    print_info "Retrying after clearing port 80..."
    sudo lsof -ti :80 | xargs -r sudo kill -9 2>/dev/null || true
    sleep 2
    
    NGINX_START_OUTPUT=$(sudo systemctl start nginx 2>&1)
    if [ $? -ne 0 ]; then
        print_error "Nginx still failed to start!"
        log_command_error "systemctl start nginx (retry)" "$NGINX_START_OUTPUT"
    fi
fi

NGINX_ENABLE_OUTPUT=$(sudo systemctl enable nginx 2>&1)
if [ $? -ne 0 ]; then
    print_warning "Nginx enable had issues (may already be enabled)"
    log_command_error "systemctl enable nginx" "$NGINX_ENABLE_OUTPUT"
fi

# Verify Nginx is running
sleep 2
if ! systemctl is-active --quiet nginx; then
    print_error "Nginx failed to start!"
    NGINX_STATUS_OUTPUT=$(sudo systemctl status nginx 2>&1)
    log_command_error "Nginx status check" "$NGINX_STATUS_OUTPUT"
    echo "$NGINX_STATUS_OUTPUT"
    exit 1
fi
print_success "Nginx is running and enabled"

print_success "Web server fully configured and operational"

# Step 16: Setup kiosk mode autostart
print_info "Setting up kiosk mode autostart..."

# Create autostart directory for current user (with sudo to avoid permission denied)
sudo mkdir -p "$USER_HOME/.config/autostart"
sudo chown -R $CURRENT_USER:$CURRENT_USER "$USER_HOME/.config" 2>/dev/null || true

# Create kiosk autostart script with auto-detected paths
sudo tee "$USER_HOME/start-kiosk.sh" > /dev/null <<'EOF'
#!/bin/bash
# SKSU Kiosk Auto-Start Script
# Wait for system to be ready
sleep 15

# Ensure services are running
sudo systemctl start php8.2-fpm 2>/dev/null || true
sudo systemctl start nginx 2>/dev/null || true

# Wait for web server
sleep 5

# Disable screen blanking and power management
xset s off 2>/dev/null || true
xset -dpms 2>/dev/null || true
xset s noblank 2>/dev/null || true

# Hide cursor
unclutter -idle 0.1 -root &

# Find chromium (could be chromium or chromium-browser)
CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
else
    echo "ERROR: Chromium not found!"
    exit 1
fi

# Start Chromium in kiosk mode
$CHROMIUM_CMD \
    --kiosk \
    --noerrdialogs \
    --disable-infobars \
    --disable-session-crashed-bubble \
    --disable-restore-session-state \
    --no-first-run \
    --disable-component-update \
    --disable-background-networking \
    --disable-sync \
    --disable-translate \
    --disable-features=TranslateUI \
    --start-fullscreen \
    --app=http://localhost/ &
EOF

sudo chmod +x "$USER_HOME/start-kiosk.sh"
sudo chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/start-kiosk.sh"

# Create autostart desktop entry
tee "$USER_HOME/.config/autostart/kiosk.desktop" > /dev/null <<EOF
[Desktop Entry]
Type=Application
Name=SKSU Campus Kiosk
Exec=$USER_HOME/start-kiosk.sh
X-GNOME-Autostart-enabled=true
EOF

chmod +x "$USER_HOME/.config/autostart/kiosk.desktop"
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/.config/autostart/kiosk.desktop"

print_success "Kiosk mode autostart configured"

# Step 17: Create management scripts
print_info "Creating management scripts..."

# Find chromium command
CHROMIUM_CMD="chromium"
if ! command -v chromium &> /dev/null; then
    if command -v chromium-browser &> /dev/null; then
        CHROMIUM_CMD="chromium-browser"
    fi
fi

# Start script
sudo tee "$USER_HOME/kiosk-start.sh" > /dev/null <<STARTEOF
#!/bin/bash
echo "Starting SKSU Kiosk services..."
sudo systemctl start php8.2-fpm
sudo systemctl start nginx

# Wait for services
sleep 3

# Check if services are running
if systemctl is-active --quiet php8.2-fpm && systemctl is-active --quiet nginx; then
    echo "✅ Services started successfully"
    # Start browser in kiosk mode
    DISPLAY=:0 $CHROMIUM_CMD --kiosk --app=http://localhost/ &
    echo "✅ Kiosk browser launched"
else
    echo "❌ ERROR: Services failed to start"
    systemctl status php8.2-fpm
    systemctl status nginx
fi
STARTEOF
sudo chmod +x "$USER_HOME/kiosk-start.sh"
sudo chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/kiosk-start.sh"

# Stop script
sudo tee "$USER_HOME/kiosk-stop.sh" > /dev/null <<STOPEOF
#!/bin/bash
echo "Stopping SKSU Kiosk..."
pkill -f chromium 2>/dev/null || true
pkill -f chromium-browser 2>/dev/null || true
sudo systemctl stop nginx 2>/dev/null || true
sudo systemctl stop php8.2-fpm 2>/dev/null || true
echo "✅ SKSU Kiosk stopped"
STOPEOF
sudo chmod +x "$USER_HOME/kiosk-stop.sh"
sudo chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/kiosk-stop.sh"

# Restart script
sudo tee "$USER_HOME/kiosk-restart.sh" > /dev/null <<RESTARTEOF
#!/bin/bash
echo "Restarting SKSU Kiosk..."
$USER_HOME/kiosk-stop.sh
sleep 3
$USER_HOME/kiosk-start.sh
RESTARTEOF
sudo chmod +x "$USER_HOME/kiosk-restart.sh"
sudo chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/kiosk-restart.sh"

# Update script with auto USB detection
sudo tee "$USER_HOME/kiosk-update.sh" > /dev/null <<'UPDATEEOF'
#!/bin/bash
echo "Updating SKSU Kiosk from USB drive..."

# Auto-detect USB drive with Navi folder
USB_PATH=""
for mount in /media/*/* /mnt/*; do
    if [ -d "$mount/Navi" ]; then
        USB_PATH="$mount/Navi"
        break
    fi
done

if [ -z "$USB_PATH" ]; then
    echo "❌ ERROR: USB drive with Navi folder not found"
    echo "Please plug in USB drive with Navi folder"
    exit 1
fi

echo "✅ Found USB at: $USB_PATH"
echo "Updating files..."

# Backup current .env
if [ -f "/home/pi/sksu-kiosk/.env" ]; then
    cp /home/pi/sksu-kiosk/.env /tmp/kiosk-env-backup
fi

# Copy files
sudo rsync -av --exclude=storage --exclude=.env --exclude=database/database.sqlite "$USB_PATH/" "/home/pi/sksu-kiosk/"
sudo chown -R www-data:www-data "/home/pi/sksu-kiosk"

# Restore .env if it existed
if [ -f "/tmp/kiosk-env-backup" ]; then
    sudo cp /tmp/kiosk-env-backup /home/pi/sksu-kiosk/.env
    sudo chown www-data:www-data /home/pi/sksu-kiosk/.env
fi

cd "/home/pi/sksu-kiosk"
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

echo "✅ Update completed successfully"
UPDATEEOF
sudo chmod +x "$USER_HOME/kiosk-update.sh"
sudo chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/kiosk-update.sh"

print_success "Management scripts created"

# Step 18: Final Laravel optimization
print_info "Optimizing Laravel..."
cd "$PROJECT_DIR"
sudo -u www-data php artisan config:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
sudo -u www-data php artisan route:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
sudo -u www-data php artisan view:cache 2>&1 | tee -a "$FULL_LOG" > /dev/null
print_success "Laravel optimized"

# Step 19: Create desktop shortcuts
print_info "Creating desktop shortcuts..."
sudo mkdir -p "$USER_HOME/Desktop"
sudo chown -R $CURRENT_USER:$CURRENT_USER "$USER_HOME/Desktop"

tee "$USER_HOME/Desktop/kiosk-start.desktop" > /dev/null <<EOF
[Desktop Entry]
Type=Application
Name=Start Kiosk
Icon=system-run
Exec=$USER_HOME/kiosk-start.sh
Terminal=true
EOF

tee "$USER_HOME/Desktop/kiosk-stop.desktop" > /dev/null <<EOF
[Desktop Entry]
Type=Application
Name=Stop Kiosk
Icon=system-shutdown
Exec=$USER_HOME/kiosk-stop.sh
Terminal=true
EOF

chmod +x "$USER_HOME/Desktop"/*.desktop
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/Desktop"/*.desktop

print_success "Desktop shortcuts created"

# Copy logs to user home and USB for easy access
print_info "Saving logs to USB and home directory..."
cp "$LOG_FILE" "$USER_HOME/kiosk-installation.log" 2>/dev/null || true
cp "$ERROR_LOG" "$USER_HOME/kiosk-errors.log" 2>/dev/null || true
cp "$FULL_LOG" "$USER_HOME/kiosk-full-log.log" 2>/dev/null || true
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME"/kiosk-*.log 2>/dev/null || true

# Final message
{
    echo ""
    echo "============================================================"
    echo "   ✅ DEPLOYMENT COMPLETED SUCCESSFULLY!"
    echo "============================================================"
    echo ""
    echo "📋 Installation Summary:"
    echo "   • Project Location: $PROJECT_DIR"
    echo "   • Database: SQLite (database.sqlite)"
    echo "   • Web Server: Nginx (http://localhost)"
    echo "   • Auto-start: Enabled on boot"
    echo ""
    echo "🎮 Management Commands:"
    echo "   • Start Kiosk:   ~/kiosk-start.sh"
    echo "   • Stop Kiosk:    ~/kiosk-stop.sh"
    echo "   • Restart Kiosk: ~/kiosk-restart.sh"
    echo "   • Update Kiosk:  ~/kiosk-update.sh"
    echo ""
    echo "📁 Log Files (Saved to USB):"
    echo "   • Full Log:   $FULL_LOG"
    echo "   • Error Log:  $ERROR_LOG"
    echo "   • Also saved: ~/kiosk-*.log"
    echo ""
    echo "🔄 The system will start automatically on next boot"
} | tee -a "$FULL_LOG"

# Final error log summary
if [ -s "$ERROR_LOG" ]; then
    ERROR_COUNT=$(grep -c "ERROR:" "$ERROR_LOG" 2>/dev/null || echo "0")
    WARNING_COUNT=$(grep -c "WARNING:" "$ERROR_LOG" 2>/dev/null || echo "0")
    if [ "$ERROR_COUNT" -gt 0 ] || [ "$WARNING_COUNT" -gt 0 ]; then
        {
            echo ""
            echo "⚠️  Issues detected during installation:"
            echo "   • Errors: $ERROR_COUNT"
            echo "   • Warnings: $WARNING_COUNT"
            echo "   • Check logs: $FULL_LOG"
            echo "   • Or: ~/kiosk-errors.log"
        } | tee -a "$FULL_LOG"
    fi
fi

{
    echo ""
    echo "💡 Reboot now? (y/n)"
} | tee -a "$FULL_LOG"
read -r response
if [[ "$response" =~ ^[Yy]$ ]]; then
    echo "Rebooting system..." | tee -a "$FULL_LOG"
    sudo reboot
else
    {
        echo ""
        echo "✅ Deployment complete! Logs saved to USB:"
        echo "   📄 Full Log: $FULL_LOG"
        echo "   ⚠️  Errors:   $ERROR_LOG"
        echo ""
        echo "💡 You can start the kiosk by running:"
        echo "   bash ~/kiosk-start.sh"
    } | tee -a "$FULL_LOG"
fi
