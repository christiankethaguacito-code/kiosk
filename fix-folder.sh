#!/bin/bash
#############################################################
# Quick Fix: Case-Sensitive Folder Name
#############################################################

echo "Fixing case-sensitive views folder..."

PROJECT_DIR="/home/pi/sksu-kiosk"
cd "$PROJECT_DIR/resources/views"

echo "Current folder listing:"
ls -la

# Check what exists
if [ -d "Layouts" ]; then
    echo ""
    echo "Found 'Layouts' (capital L) - renaming to 'layouts'..."
    mv Layouts layouts_temp
    mv layouts_temp layouts
    echo "✅ Renamed successfully!"
elif [ -d "layouts" ]; then
    echo "✅ 'layouts' folder already exists (lowercase)"
else
    echo "❌ Neither Layouts nor layouts found!"
    echo ""
    echo "Creating layouts folder and copying from USB..."
    
    # Try to copy from USB
    USB_PATH=""
    for mount in /media/*/* /mnt/*; do
        if [ -d "$mount/Navi/resources/views/Layouts" ]; then
            USB_PATH="$mount/Navi/resources/views/Layouts"
            break
        elif [ -d "$mount/Navi/resources/views/layouts" ]; then
            USB_PATH="$mount/Navi/resources/views/layouts"
            break
        fi
    done
    
    if [ -n "$USB_PATH" ]; then
        echo "Found on USB: $USB_PATH"
        sudo cp -r "$USB_PATH" "$PROJECT_DIR/resources/views/layouts"
        sudo chown -R www-data:www-data "$PROJECT_DIR/resources/views/layouts"
        echo "✅ Copied from USB"
    fi
fi

echo ""
echo "After fix - folder listing:"
ls -la

# Clear view cache
echo ""
echo "Clearing Laravel caches..."
cd "$PROJECT_DIR"
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear

# Restart services
echo ""
echo "Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

echo ""
echo "✅ Fix complete! Try accessing http://localhost/login now"
