@echo off
REM ============================================
REM SYNC KIOSK TO RASPBERRY PI
REM Creates a sync package for USB transfer
REM ============================================

echo ========================================
echo   KIOSK SYNC PACKAGE CREATOR
echo   For Raspberry Pi Update
echo ========================================
echo.

REM Get the script directory
set SCRIPT_DIR=%~dp0
cd /d %SCRIPT_DIR%

REM Create sync folder on D: drive
set SYNC_DIR=D:\kiosk-sync
echo [1/6] Creating sync directory: %SYNC_DIR%
if exist "%SYNC_DIR%" rmdir /s /q "%SYNC_DIR%"
mkdir "%SYNC_DIR%"
mkdir "%SYNC_DIR%\storage\announcements"
mkdir "%SYNC_DIR%\storage\buildings"
mkdir "%SYNC_DIR%\database"
echo   Done!
echo.

REM Copy database
echo [2/6] Copying database...
copy "database\database.sqlite" "%SYNC_DIR%\database\database.sqlite" >nul
echo   Done!
echo.

REM Copy announcement images
echo [3/6] Copying announcement images...
if exist "storage\app\public\announcements\*" (
    xcopy "storage\app\public\announcements\*" "%SYNC_DIR%\storage\announcements\" /s /y /q
    echo   Done!
) else (
    echo   No announcement images found
)
echo.

REM Copy building images
echo [4/6] Copying building images...
if exist "storage\app\public\buildings\*" (
    xcopy "storage\app\public\buildings\*" "%SYNC_DIR%\storage\buildings\" /s /y /q
    echo   Done!
) else (
    echo   No building images found
)
echo.

REM Copy the update script for Pi
echo [5/6] Creating Pi update script...
(
echo #!/bin/bash
echo #============================================
echo # UPDATE KIOSK FROM SYNC PACKAGE
echo # Run this on Raspberry Pi after USB transfer
echo #============================================
echo.
echo echo "========================================"
echo echo "  KIOSK UPDATE FROM SYNC PACKAGE"
echo echo "========================================"
echo echo ""
echo.
echo # Find the USB mount point
echo USB_PATH=""
echo for mount in /media/pi/* /media/$USER/* /mnt/*; do
echo     if [ -d "$mount/kiosk-sync" ]; then
echo         USB_PATH="$mount/kiosk-sync"
echo         break
echo     fi
echo done
echo.
echo if [ -z "$USB_PATH" ]; then
echo     echo "ERROR: Could not find kiosk-sync folder on USB!"
echo     echo "Make sure USB is mounted and contains kiosk-sync folder"
echo     exit 1
echo fi
echo.
echo echo "Found sync package at: $USB_PATH"
echo echo ""
echo.
echo # Get the Navi directory ^(same as this script^)
echo NAVI_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}"^)" ^&^& pwd^)"
echo cd "$NAVI_DIR"
echo echo "Updating kiosk at: $NAVI_DIR"
echo echo ""
echo.
echo # Backup current database
echo echo "[1/5] Backing up current database..."
echo if [ -f "database/database.sqlite" ]; then
echo     cp "database/database.sqlite" "database/database.sqlite.backup.$(date +%%Y%%m%%d_%%H%%M%%S^)"
echo     echo "  Backup created"
echo fi
echo echo ""
echo.
echo # Copy new database
echo echo "[2/5] Updating database..."
echo cp "$USB_PATH/database/database.sqlite" "database/database.sqlite"
echo chmod 664 "database/database.sqlite"
echo echo "  Database updated"
echo echo ""
echo.
echo # Ensure storage directories exist
echo echo "[3/5] Preparing storage directories..."
echo mkdir -p storage/app/public/announcements
echo mkdir -p storage/app/public/buildings
echo echo "  Directories ready"
echo echo ""
echo.
echo # Copy announcement images
echo echo "[4/5] Copying announcement images..."
echo if [ -d "$USB_PATH/storage/announcements" ]; then
echo     cp -r "$USB_PATH/storage/announcements/"* "storage/app/public/announcements/" 2^>/dev/null ^|^| true
echo     echo "  Announcements copied"
echo fi
echo echo ""
echo.
echo # Copy building images  
echo echo "[5/5] Copying building images..."
echo if [ -d "$USB_PATH/storage/buildings" ]; then
echo     cp -r "$USB_PATH/storage/buildings/"* "storage/app/public/buildings/" 2^>/dev/null ^|^| true
echo     echo "  Buildings copied"
echo fi
echo echo ""
echo.
echo # Fix permissions
echo echo "Fixing permissions..."
echo chmod -R 775 storage
echo chmod -R 775 database
echo.
echo # Recreate storage symlink
echo echo "Recreating storage symlink..."
echo rm -f public/storage 2^>/dev/null
echo php artisan storage:link 2^>/dev/null ^|^| ln -sf "$NAVI_DIR/storage/app/public" "$NAVI_DIR/public/storage"
echo.
echo # Clear caches
echo echo "Clearing caches..."
echo php artisan cache:clear 2^>/dev/null
echo php artisan view:clear 2^>/dev/null
echo php artisan config:clear 2^>/dev/null
echo.
echo # Set ownership if running as root
echo if [ "$EUID" -eq 0 ] ^|^| [ -n "$SUDO_USER" ]; then
echo     chown -R www-data:www-data storage database public/storage 2^>/dev/null
echo fi
echo.
echo echo ""
echo echo "========================================"
echo echo "  UPDATE COMPLETE!"
echo echo "========================================"
echo echo ""
echo echo "Restart your web server:"
echo echo "  sudo systemctl restart apache2"
echo echo "  OR php artisan serve --host=0.0.0.0"
echo echo ""
) > "%SYNC_DIR%\update-from-sync.sh"

echo   Done!
echo.

REM Also copy it to Navi folder in D:
echo [6/6] Copying update script to D:\Navi...
copy "%SYNC_DIR%\update-from-sync.sh" "D:\Navi\update-from-sync.sh" >nul 2>nul
echo   Done!
echo.

echo ========================================
echo   SYNC PACKAGE CREATED!
echo ========================================
echo.
echo Location: %SYNC_DIR%
echo.
echo Contents:
dir /b "%SYNC_DIR%"
echo.
echo ----------------------------------------
echo HOW TO USE:
echo ----------------------------------------
echo 1. Copy D:\kiosk-sync folder to a USB drive
echo 2. Insert USB into Raspberry Pi
echo 3. On Raspberry Pi, run:
echo      cd /path/to/Navi
echo      chmod +x update-from-sync.sh
echo      sudo ./update-from-sync.sh
echo ----------------------------------------
echo.
pause
