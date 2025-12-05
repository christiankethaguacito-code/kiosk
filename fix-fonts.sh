#!/bin/bash
#############################################################
# Fix Font Display Issues - Install Fonts & Update Config
# Run this to fix box characters/unreadable text in kiosk
#############################################################

echo "============================================"
echo "   Fixing Font Display Issues"
echo "============================================"
echo ""

# Step 1: Install fonts
echo "📦 Installing missing fonts..."
sudo apt-get update -qq
sudo apt-get install -y fonts-liberation fonts-noto fonts-noto-cjk fonts-noto-color-emoji ttf-mscorefonts-installer

if [ $? -eq 0 ]; then
    echo "✅ Fonts installed successfully"
else
    echo "❌ Font installation failed"
    exit 1
fi

# Step 2: Update font cache
echo "🔄 Updating font cache..."
sudo fc-cache -f -v > /dev/null 2>&1
echo "✅ Font cache updated"

# Step 3: Update Nginx configuration for UTF-8
echo "⚙️  Configuring Nginx for UTF-8..."

NGINX_CONFIG="/etc/nginx/sites-available/kiosk"

if [ ! -f "$NGINX_CONFIG" ]; then
    echo "❌ Nginx config not found at $NGINX_CONFIG"
    exit 1
fi

# Backup original config
sudo cp "$NGINX_CONFIG" "$NGINX_CONFIG.backup"
echo "📋 Backup created: $NGINX_CONFIG.backup"

# Add charset utf-8 after server_name if not exists
if ! grep -q "charset utf-8;" "$NGINX_CONFIG"; then
    sudo sed -i '/server_name _;/a \    \n    charset utf-8;' "$NGINX_CONFIG"
    echo "✅ Added charset utf-8 to Nginx config"
else
    echo "ℹ️  charset utf-8 already exists"
fi

# Add PHP UTF-8 parameter if not exists
if ! grep -q "PHP_VALUE.*UTF-8" "$NGINX_CONFIG"; then
    sudo sed -i '/fastcgi_param SCRIPT_FILENAME/a \        fastcgi_param PHP_VALUE "default_charset=UTF-8";' "$NGINX_CONFIG"
    echo "✅ Added PHP UTF-8 parameter"
else
    echo "ℹ️  PHP UTF-8 parameter already exists"
fi

# Step 4: Test Nginx configuration
echo "🧪 Testing Nginx configuration..."
sudo nginx -t > /dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Nginx configuration is valid"
else
    echo "❌ Nginx configuration test failed!"
    echo "Restoring backup..."
    sudo cp "$NGINX_CONFIG.backup" "$NGINX_CONFIG"
    exit 1
fi

# Step 5: Restart services
echo "🔄 Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

if systemctl is-active --quiet nginx && systemctl is-active --quiet php8.2-fpm; then
    echo "✅ Services restarted successfully"
else
    echo "❌ Service restart failed"
    exit 1
fi

# Step 6: Restart kiosk
echo "🖥️  Restarting kiosk..."
if [ -f "$HOME/kiosk-stop.sh" ]; then
    bash "$HOME/kiosk-stop.sh" > /dev/null 2>&1
    sleep 2
    bash "$HOME/kiosk-start.sh" > /dev/null 2>&1
    echo "✅ Kiosk restarted"
else
    echo "⚠️  Kiosk scripts not found, restart manually"
fi

echo ""
echo "============================================"
echo "   ✅ Font fix completed!"
echo "   The text should now display properly"
echo "============================================"
