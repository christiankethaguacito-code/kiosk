@echo off
REM ============================================================
REM SKSU Campus Kiosk - USB Preparation Script for Windows
REM Prepares the USB drive for Raspberry Pi deployment
REM ============================================================

echo ============================================================
echo    SKSU Campus Kiosk - USB Preparation
echo    Preparing files for Raspberry Pi 5 deployment
echo ============================================================
echo.

REM Check if running from Navi directory
if not exist "artisan" (
    echo ERROR: Please run this script from the Navi project directory
    pause
    exit /b 1
)

echo [1/6] Checking project structure...
if not exist "public" (
    echo ERROR: public directory not found
    pause
    exit /b 1
)
echo       Project structure OK

echo.
echo [2/6] Cleaning up temporary files...
if exist "storage\logs\*.log" del /q "storage\logs\*.log"
if exist "storage\framework\cache\*" del /q "storage\framework\cache\*"
if exist "bootstrap\cache\*.php" del /q "bootstrap\cache\*.php"
echo       Temporary files cleaned

echo.
echo [3/6] Verifying auto-run files...
if not exist "install.sh" (
    echo ERROR: install.sh not found
    set MISSING=1
)
if not exist "setup-autorun.sh" (
    echo ERROR: setup-autorun.sh not found
    set MISSING=1
)
if not exist "kiosk-usb-handler.sh" (
    echo ERROR: kiosk-usb-handler.sh not found
    set MISSING=1
)
if not exist "99-kiosk-usb.rules" (
    echo ERROR: 99-kiosk-usb.rules not found
    set MISSING=1
)
echo       Auto-run scripts verified

echo.
echo [4/6] Creating deployment package info...
echo SKSU Campus Kiosk Deployment Package > PACKAGE_INFO.txt
echo Created: %date% %time% >> PACKAGE_INFO.txt
echo Version: 1.0.0 >> PACKAGE_INFO.txt
echo Target: Raspberry Pi 5 with Linux >> PACKAGE_INFO.txt
echo. >> PACKAGE_INFO.txt
echo Deployment Instructions: >> PACKAGE_INFO.txt
echo 1. Copy entire folder to USB drive >> PACKAGE_INFO.txt
echo 2. Plug USB into Raspberry Pi 5 >> PACKAGE_INFO.txt
echo 3. Open terminal and run: bash AUTORUN.sh >> PACKAGE_INFO.txt
echo 4. Wait for installation to complete >> PACKAGE_INFO.txt
echo 5. Reboot when prompted >> PACKAGE_INFO.txt
echo       Package info created

echo.
echo [5/6] Verifying critical files...
set MISSING=0

if not exist "composer.json" (
    echo ERROR: composer.json not found
    set MISSING=1
)
if not exist "package.json" (
    echo ERROR: package.json not found
    set MISSING=1
)
if not exist ".env.example" (
    echo ERROR: .env.example not found
    set MISSING=1
)
if not exist "deploy-kiosk.sh" (
    echo ERROR: deploy-kiosk.sh not found
    set MISSING=1
)
if not exist "AUTORUN.sh" (
    echo ERROR: AUTORUN.sh not found
    set MISSING=1
)
if not exist "README-DEPLOYMENT.md" (
    echo ERROR: README-DEPLOYMENT.md not found
    set MISSING=1
)
if not exist "database\database.sqlite" (
    echo WARNING: database\database.sqlite not found
    echo The system will create a new empty database
    set MISSING=1
)

if %MISSING%==1 (
    echo.
    echo WARNING: Some files are missing (see above)
    echo Continue anyway? (Ctrl+C to cancel, any key to continue)
    pause > nul
)
echo       Critical files verified

echo.
echo [6/6] Creating USB copy instructions...
(
echo ============================================================
echo    COPY THESE FILES TO YOUR USB DRIVE
echo ============================================================
echo.
echo 1. Copy the ENTIRE Navi folder to your USB drive
echo.
echo 2. Eject USB safely from Windows
echo.
echo 3. Plug USB into Raspberry Pi 5
echo.
echo 4. Open Terminal on Raspberry Pi
echo.
echo 5. Navigate to USB drive:
echo    cd /media/pi/YOUR_USB_NAME/Navi
echo.
echo 6. Run: bash AUTORUN.sh
echo.
echo 7. Choose Option 1 for TRUE PLUG-AND-PLAY
echo    (Only needed ONCE - then just plug USB anytime!)
echo.
echo OR Choose Option 2 for manual installation now
echo.
echo ============================================================
echo    IMPORTANT NOTES
echo ============================================================
echo.
echo - Ensure Raspberry Pi is connected to internet
echo - First time: Run setup-autorun.sh (Option 1 in AUTORUN.sh)
echo - After setup: Just plug USB - NO COMMANDS NEEDED!
echo - Installation takes 5-10 minutes
echo - Do not remove USB during installation
echo - System will reboot automatically when complete
echo.
echo - Default Admin Credentials:
echo   Username: admin
echo   Password: admin123
echo   (Change these after first login!)
echo.
echo ============================================================
) > USB_COPY_INSTRUCTIONS.txt
echo       Instructions created

echo.
echo ============================================================
echo    PREPARATION COMPLETE
echo ============================================================
echo.
echo Next steps:
echo 1. Copy the entire Navi folder to your USB drive
echo 2. Read USB_COPY_INSTRUCTIONS.txt for deployment steps
echo 3. Safely eject USB drive
echo 4. Proceed with Raspberry Pi deployment
echo.
echo Files ready for deployment!
echo ============================================================
echo.
pause
