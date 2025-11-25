# ==========================================
# SKSU Campus Kiosk - Database Setup Script
# ==========================================
# This script sets up the database from scratch

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Database Setup & Migration Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running in correct directory
if (-not (Test-Path "artisan")) {
    Write-Host "ERROR: Please run this script from the Navi directory!" -ForegroundColor Red
    Write-Host "Current directory: $PWD" -ForegroundColor Yellow
    pause
    exit 1
}

# Find PHP
$phpPath = $null
$phpPaths = @(
    "C:\xampp\php\php.exe",
    "C:\wamp64\bin\php\php8.2.12\php.exe",
    "C:\laragon\bin\php\php-8.2\php.exe",
    "php"
)

foreach ($path in $phpPaths) {
    try {
        if ($path -eq "php") {
            $result = & $path --version 2>&1
            if ($result -match "PHP") {
                $phpPath = $path
                break
            }
        }
        else {
            if (Test-Path $path) {
                $phpPath = $path
                break
            }
        }
    }
    catch {
        continue
    }
}

if (-not $phpPath) {
    Write-Host "ERROR: PHP not found!" -ForegroundColor Red
    Write-Host "Please install XAMPP or add PHP to your PATH" -ForegroundColor Yellow
    pause
    exit 1
}

Write-Host "Using PHP: $phpPath" -ForegroundColor Green
Write-Host ""

# Ask for confirmation
Write-Host "WARNING: This will DELETE the existing database and create a new one!" -ForegroundColor Yellow
Write-Host "All current data will be lost." -ForegroundColor Yellow
Write-Host ""
$confirm = Read-Host "Are you sure you want to continue? (yes/no)"

if ($confirm -ne "yes") {
    Write-Host "Operation cancelled." -ForegroundColor Yellow
    pause
    exit 0
}

Write-Host ""
Write-Host "[1/5] Removing old database..." -ForegroundColor Yellow

if (Test-Path "database/database.sqlite") {
    Remove-Item "database/database.sqlite" -Force
    Write-Host "✓ Old database removed" -ForegroundColor Green
}
else {
    Write-Host "✓ No existing database found" -ForegroundColor Green
}
Write-Host ""

Write-Host "[2/5] Creating new database..." -ForegroundColor Yellow
New-Item -Path "database/database.sqlite" -ItemType File -Force | Out-Null
Write-Host "✓ Database file created" -ForegroundColor Green
Write-Host ""

Write-Host "[3/5] Running migrations..." -ForegroundColor Yellow
& $phpPath artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Migration failed!" -ForegroundColor Red
    pause
    exit 1
}
Write-Host "✓ Migrations completed" -ForegroundColor Green
Write-Host ""

Write-Host "[4/5] Seeding admin user..." -ForegroundColor Yellow
& $phpPath artisan db:seed --class=AdminSeeder --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Admin seeding failed!" -ForegroundColor Red
    pause
    exit 1
}
Write-Host "✓ Admin user created (username: admin, password: password)" -ForegroundColor Green
Write-Host ""

Write-Host "[5/5] Importing building data..." -ForegroundColor Yellow
& $phpPath artisan db:seed --class=JsonBuildingSeeder --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Building data import failed!" -ForegroundColor Red
    pause
    exit 1
}
Write-Host "✓ Building data imported" -ForegroundColor Green
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "  Database Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "DATABASE SUMMARY:" -ForegroundColor Cyan
Write-Host "  - Admin Username: admin" -ForegroundColor White
Write-Host "  - Admin Password: password" -ForegroundColor White
Write-Host "  - Buildings imported: 15" -ForegroundColor White
Write-Host "  - Offices imported: 49" -ForegroundColor White
Write-Host "  - Services imported: 144" -ForegroundColor White
Write-Host ""
Write-Host "You can now start the server with:" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Or run the full setup script:" -ForegroundColor Yellow
Write-Host "  .\setup.ps1" -ForegroundColor White
Write-Host ""

pause
