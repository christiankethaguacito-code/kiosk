#!/bin/bash
#############################################################
# Fix PHP/Nginx Configuration - Fixes garbled binary output
#############################################################

echo "============================================"
echo "   Diagnosing & Fixing PHP/Nginx Issue"
echo "============================================"
echo ""

# Step 1: Check PHP-FPM status
echo "🔍 Checking PHP-FPM..."
if systemctl is-active --quiet php8.2-fpm; then
    echo "✅ PHP-FPM is running"
else
    echo "❌ PHP-FPM is NOT running!"
    echo "Starting PHP-FPM..."
    sudo systemctl start php8.2-fpm
fi

# Step 2: Check if PHP-FPM socket exists
echo ""
echo "🔍 Checking PHP-FPM socket..."
if [ -S /var/run/php/php8.2-fpm.sock ]; then
    echo "✅ PHP-FPM socket exists"
else
    echo "❌ PHP-FPM socket missing!"
    echo "Restarting PHP-FPM..."
    sudo systemctl restart php8.2-fpm
    sleep 2
fi

# Step 3: Check Nginx status
echo ""
echo "🔍 Checking Nginx..."
if systemctl is-active --quiet nginx; then
    echo "✅ Nginx is running"
else
    echo "❌ Nginx is NOT running!"
    echo "Starting Nginx..."
    sudo systemctl start nginx
fi

# Step 4: Fix Nginx configuration
echo ""
echo "⚙️  Fixing Nginx configuration..."

PROJECT_DIR="/home/pi/sksu-kiosk"

sudo tee /etc/nginx/sites-available/kiosk > /dev/null <<'NGINXEOF'
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    root /home/pi/sksu-kiosk/public;
    index index.php index.html;
    
    server_name _;
    
    charset utf-8;
    
    # Disable gzip to prevent encoding issues
    gzip off;
    
    # Add proper headers
    add_header X-Content-Type-Options "nosniff" always;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Force text/html content type
        fastcgi_param PHP_VALUE "default_charset=UTF-8";
        add_header Content-Type "text/html; charset=UTF-8" always;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    # Handle static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
NGINXEOF

echo "✅ Nginx configuration updated"

# Step 5: Remove default nginx site
echo ""
echo "🗑️  Removing default Nginx site..."
sudo rm -f /etc/nginx/sites-enabled/default

# Step 6: Enable kiosk site
sudo ln -sf /etc/nginx/sites-available/kiosk /etc/nginx/sites-enabled/kiosk

# Step 7: Test Nginx config
echo ""
echo "🧪 Testing Nginx configuration..."
NGINX_TEST=$(sudo nginx -t 2>&1)
if echo "$NGINX_TEST" | grep -q "successful"; then
    echo "✅ Nginx configuration is valid"
else
    echo "❌ Nginx configuration error:"
    echo "$NGINX_TEST"
    exit 1
fi

# Step 8: Check Laravel project
echo ""
echo "🔍 Checking Laravel project..."
if [ -f "$PROJECT_DIR/public/index.php" ]; then
    echo "✅ Laravel public/index.php exists"
else
    echo "❌ Laravel public/index.php NOT found!"
    echo "Project may not be properly installed"
    exit 1
fi

# Step 9: Fix permissions
echo ""
echo "🔐 Fixing permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
echo "✅ Permissions fixed"

# Step 10: Clear Laravel cache
echo ""
echo "🧹 Clearing Laravel cache..."
cd "$PROJECT_DIR"
sudo -u www-data php artisan config:clear 2>/dev/null || true
sudo -u www-data php artisan cache:clear 2>/dev/null || true
sudo -u www-data php artisan view:clear 2>/dev/null || true
sudo -u www-data php artisan route:clear 2>/dev/null || true
echo "✅ Cache cleared"

# Step 11: Restart services
echo ""
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sleep 2
sudo systemctl restart nginx
sleep 2
echo "✅ Services restarted"

# Step 12: Test with curl
echo ""
echo "🧪 Testing HTTP response..."
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/)
echo "HTTP Response Code: $RESPONSE"

if [ "$RESPONSE" = "200" ]; then
    echo "✅ Server responding with 200 OK"
else
    echo "⚠️  Server responding with: $RESPONSE"
fi

# Check content type
echo ""
echo "🔍 Checking content type..."
CONTENT_TYPE=$(curl -sI http://localhost/ | grep -i "content-type" | head -1)
echo "$CONTENT_TYPE"

if echo "$CONTENT_TYPE" | grep -qi "text/html"; then
    echo "✅ Content-Type is text/html"
else
    echo "⚠️  Content-Type might be wrong"
fi

# Step 13: Show first 500 bytes of response
echo ""
echo "📄 First 500 characters of response:"
echo "-----------------------------------"
curl -s http://localhost/ | head -c 500
echo ""
echo "-----------------------------------"

# Step 14: Stop existing kiosk and restart
echo ""
echo "🖥️  Restarting kiosk..."
pkill -f chromium 2>/dev/null || true
pkill -f chromium-browser 2>/dev/null || true
sleep 2

# Find chromium command
CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -n "$CHROMIUM_CMD" ]; then
    echo "Starting kiosk with $CHROMIUM_CMD..."
    DISPLAY=:0 $CHROMIUM_CMD --kiosk --app=http://localhost/ &
    echo "✅ Kiosk browser launched"
fi

echo ""
echo "============================================"
echo "   ✅ Fix completed!"
echo "   Check if the page displays correctly now"
echo "============================================"
