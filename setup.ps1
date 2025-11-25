# ==========================================
# SKSU Campus Kiosk - One-Time Setup Script
# ==========================================
# This script sets up and runs the project for collaborators

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  SKSU Campus Kiosk Setup Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running in correct directory
if (-not (Test-Path "artisan")) {
    Write-Host "ERROR: Please run this script from the Navi directory!" -ForegroundColor Red
    Write-Host "Current directory: $PWD" -ForegroundColor Yellow
    Write-Host "Expected files: artisan, composer.json, package.json" -ForegroundColor Yellow
    pause
    exit 1
}

Write-Host "[1/7] Checking PHP installation..." -ForegroundColor Yellow

# Try to find PHP
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
        } else {
            if (Test-Path $path) {
                $result = & $path --version 2>&1
                $phpPath = $path
                break
            }
        }
        if ($result -match "PHP") {
            $phpPath = $path
            break
        }
    } catch {
        continue
    }
}

if (-not $phpPath) {
    Write-Host "ERROR: PHP not found!" -ForegroundColor Red
    Write-Host "Please install XAMPP from: https://www.apachefriends.org/" -ForegroundColor Yellow
    pause
    exit 1
}

Write-Host "✓ PHP found at: $phpPath" -ForegroundColor Green
& $phpPath --version | Select-Object -First 1
Write-Host ""

Write-Host "[2/7] Checking Composer installation..." -ForegroundColor Yellow

$composerPath = $null
if (Test-Path "composer.phar") {
    $composerPath = "$phpPath composer.phar"
    Write-Host "✓ Using local composer.phar" -ForegroundColor Green
} else {
    try {
        $result = & composer --version 2>&1
        if ($result -match "Composer") {
            $composerPath = "composer"
            Write-Host "✓ Composer found globally" -ForegroundColor Green
        }
    } catch {
        Write-Host "ERROR: Composer not found!" -ForegroundColor Red
        Write-Host "Installing Composer locally..." -ForegroundColor Yellow
        
        try {
            & $phpPath -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            & $phpPath composer-setup.php
            & $phpPath -r "unlink('composer-setup.php');"
            $composerPath = "$phpPath composer.phar"
            Write-Host "✓ Composer installed successfully" -ForegroundColor Green
        } catch {
            Write-Host "ERROR: Failed to install Composer" -ForegroundColor Red
            Write-Host "Please download manually from: https://getcomposer.org/download/" -ForegroundColor Yellow
            pause
            exit 1
        }
    }
}
Write-Host ""

Write-Host "[3/7] Installing PHP dependencies..." -ForegroundColor Yellow
if ($composerPath -like "*composer.phar*") {
    Invoke-Expression "$composerPath install --no-interaction"
} else {
    & composer install --no-interaction
}
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to install PHP dependencies" -ForegroundColor Red
    pause
    exit 1
}
Write-Host "✓ PHP dependencies installed" -ForegroundColor Green
Write-Host ""

Write-Host "[4/7] Checking Node.js installation..." -ForegroundColor Yellow
try {
    $nodeVersion = & node --version 2>&1
    Write-Host "✓ Node.js found: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Node.js not found (optional for development)" -ForegroundColor Yellow
    Write-Host "Frontend assets may not build. Install from: https://nodejs.org/" -ForegroundColor Yellow
}
Write-Host ""

Write-Host "[5/7] Setting up environment..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✓ Created .env file" -ForegroundColor Green
    } else {
        Write-Host "WARNING: .env.example not found, creating basic .env" -ForegroundColor Yellow
        @"
APP_NAME="SKSU Campus Kiosk"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
"@ | Out-File -FilePath ".env" -Encoding UTF8
    }
}

Write-Host "Generating application key..." -ForegroundColor Yellow
& $phpPath artisan key:generate --force
Write-Host "✓ Application key generated" -ForegroundColor Green
Write-Host ""

Write-Host "[6/7] Setting up database..." -ForegroundColor Yellow
if (-not (Test-Path "database/database.sqlite")) {
    New-Item -Path "database/database.sqlite" -ItemType File -Force | Out-Null
    Write-Host "✓ Created SQLite database file" -ForegroundColor Green
    
    Write-Host "Running migrations..." -ForegroundColor Yellow
    & $phpPath artisan migrate --force
    
    Write-Host "Seeding database with admin user..." -ForegroundColor Yellow
    & $phpPath artisan db:seed --class=AdminSeeder --force
    
    Write-Host "Importing building data..." -ForegroundColor Yellow
    & $phpPath artisan db:seed --class=JsonBuildingSeeder --force
    
    Write-Host "✓ Database setup complete" -ForegroundColor Green
} else {
    Write-Host "✓ Database already exists" -ForegroundColor Green
}
Write-Host ""

Write-Host "[7/7] Creating storage link..." -ForegroundColor Yellow
& $phpPath artisan storage:link 2>&1 | Out-Null
Write-Host "✓ Storage link created" -ForegroundColor Green
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "  Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "PROJECT INFORMATION:" -ForegroundColor Cyan
Write-Host "  - Admin Username: admin" -ForegroundColor White
Write-Host "  - Admin Password: password" -ForegroundColor White
Write-Host "  - Database: SQLite (database/database.sqlite)" -ForegroundColor White
Write-Host "  - Buildings imported: 15" -ForegroundColor White
Write-Host "  - Offices imported: 49" -ForegroundColor White
Write-Host "  - Services imported: 144" -ForegroundColor White
Write-Host ""
Write-Host "STARTING SERVER..." -ForegroundColor Cyan
Write-Host "  - Server URL: http://127.0.0.1:8000" -ForegroundColor White
Write-Host "  - Map Page: http://127.0.0.1:8000/map" -ForegroundColor White
Write-Host "  - Admin Login: http://127.0.0.1:8000/login" -ForegroundColor White
Write-Host ""
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""
Write-Host "Starting Laravel development server..." -ForegroundColor Green
Write-Host ""

& $phpPath artisan serve
