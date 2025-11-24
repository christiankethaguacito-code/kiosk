# 🚀 SKSU Campus Kiosk - Complete Setup Instructions

## 📋 Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Database Setup](#database-setup)
4. [Seeding Data](#seeding-data)
5. [Running the System](#running-the-system)
6. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before starting, ensure you have:

- **PHP 8.2+** (XAMPP recommended)
- **Composer** (PHP dependency manager)
- **Git** (for cloning the repository)
- **SQLite** (included with PHP)
- **Node.js & NPM** (optional, for frontend assets)

### ✅ Check Your Environment

```bash
# Check PHP version (should be 8.2+)
php -v

# Check Composer
composer -v

# Check Git
git --version
```

---

## Installation

### Step 1: Clone the Repository

```bash
# Clone from GitHub
git clone https://github.com/christiankethaguacito-code/kiosk.git

# Navigate to project folder
cd kiosk

# Checkout your preferred branch (juls, putay, itot, or main)
git checkout juls
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (optional)
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Open `.env` file and verify SQLite configuration:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\path\to\your\project\database\database.sqlite
```

**Important:** Replace the path with your actual project path or use relative path:

```env
DB_CONNECTION=sqlite
# Leave DB_DATABASE empty or remove it - Laravel will use database/database.sqlite by default
```

### Step 5: Create SQLite Database File

```bash
# Create the database file (if it doesn't exist)
New-Item -ItemType File -Path "database\database.sqlite" -Force
```

---

## Database Setup

### Understanding Migrations

Migrations create your database tables. Think of them as version control for your database schema.

### Step 1: Run Migrations

```bash
# Run all migrations to create tables
php artisan migrate
```

This creates the following tables:
- `users` - User authentication
- `admins` - Admin users
- `buildings` - Campus buildings
- `offices` - Offices within buildings
- `services` - Services provided by offices
- `announcements` - System announcements
- `map_settings` - Map configuration

### If You Need to Reset

```bash
# Drop all tables and re-run migrations (⚠️ DELETES ALL DATA)
php artisan migrate:fresh

# Drop, re-migrate, AND seed data
php artisan migrate:fresh --seed
```

---

## Seeding Data

### What is Seeding?

Seeding populates your database with initial data. This is essential for the kiosk to work properly.

### Available Seeders

1. **DatabaseSeeder** - Runs all seeders
2. **SmartCampusSeeder** - Imports campus_data.csv (buildings, offices, services)
3. **AdminSeeder** - Creates admin user
4. **BuildingSeeder** - Sample buildings (if not using SmartCampusSeeder)
5. **OfficeSeeder** - Sample offices
6. **AnnouncementSeeder** - Sample announcements
7. **MapSettingsSeeder** - Default map settings

### Step 1: Seed Admin User

```bash
# Create admin account (REQUIRED for admin panel access)
php artisan db:seed --class=AdminSeeder
```

**Default Admin Credentials:**
- Username: `admin`
- Password: `password` (change this after first login!)

### Step 2: Seed Campus Data

```bash
# Import all campus buildings, offices, and services from CSV
php artisan db:seed --class=SmartCampusSeeder
```

This imports:
- ✅ 42 buildings from campus_data.csv
- ✅ Offices with floor numbers
- ✅ Office heads with titles
- ✅ Default services

**What it does:**
1. Reads `database/seeders/campus_data.csv`
2. Handles Excel merged cells (forward-fill logic)
3. Creates buildings, offices, and services
4. Skips empty rows
5. Shows import statistics

### Step 3: Seed Additional Data (Optional)

```bash
# Seed sample announcements
php artisan db:seed --class=AnnouncementSeeder

# Seed map settings
php artisan db:seed --class=MapSettingsSeeder

# OR run all seeders at once
php artisan db:seed
```

### Step 4: Verify Data Import

```bash
# Open Laravel Tinker (interactive console)
php artisan tinker

# Check imported data
App\Models\Building::count();  // Should show 13+ buildings
App\Models\Office::count();    // Should show 45+ offices
App\Models\Service::count();   // Should show 45+ services
App\Models\Admin::count();     // Should show 1 admin

# Exit tinker
exit
```

---

## Running the System

### Step 1: Link Storage (for images)

```bash
# Create symbolic link for public storage
php artisan storage:link
```

This allows uploaded building images to be accessible via web.

### Step 2: Start Development Server

```bash
# Start Laravel development server
php artisan serve
```

**Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

### Step 3: Access the Application

Open your browser and visit:

- **Kiosk Map:** http://127.0.0.1:8000/map
- **Welcome Screen:** http://127.0.0.1:8000/
- **Admin Login:** http://127.0.0.1:8000/login
- **Admin Dashboard:** http://127.0.0.1:8000/admin/dashboard (after login)

### Step 4: Test the System

1. **Test Map Interface:**
   - Visit http://127.0.0.1:8000/map
   - Click on building markers
   - Verify building details appear in sidebar
   - Check navigation path display

2. **Test Admin Panel:**
   - Login at http://127.0.0.1:8000/login
   - Username: `admin`, Password: `password`
   - Verify dashboard loads
   - Check buildings, offices, services pages

3. **Test API Endpoints:**
   ```bash
   # List all buildings
   curl http://127.0.0.1:8000/api/buildings
   
   # Get specific building details
   curl http://127.0.0.1:8000/api/buildings/1
   ```

---

## Complete Setup Workflow

Here's the full sequence in one place:

```bash
# 1. Clone and navigate
git clone https://github.com/christiankethaguacito-code/kiosk.git
cd kiosk
git checkout juls

# 2. Install dependencies
composer install

# 3. Setup environment
copy .env.example .env
php artisan key:generate

# 4. Create database file
New-Item -ItemType File -Path "database\database.sqlite" -Force

# 5. Run migrations
php artisan migrate

# 6. Seed data
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=SmartCampusSeeder
php artisan db:seed --class=MapSettingsSeeder

# 7. Link storage
php artisan storage:link

# 8. Start server
php artisan serve
```

Then visit: http://127.0.0.1:8000/map

---

## Troubleshooting

### Issue: "Class 'SQLite3' not found"

**Solution:** Enable SQLite in PHP
1. Open `php.ini` (in XAMPP: `C:\xampp\php\php.ini`)
2. Find and uncomment: `;extension=sqlite3`
3. Remove the `;` to make it: `extension=sqlite3`
4. Restart XAMPP/Apache

### Issue: "SQLSTATE[HY000]: General error: 1 no such table: buildings"

**Solution:** Run migrations
```bash
php artisan migrate
```

### Issue: "Target class [AdminSeeder] does not exist"

**Solution:** Update Composer autoload
```bash
composer dump-autoload
php artisan db:seed --class=AdminSeeder
```

### Issue: No building data shows on map

**Solution:** Re-seed campus data
```bash
php artisan db:seed --class=SmartCampusSeeder
```

Check if data was imported:
```bash
php artisan tinker
App\Models\Building::all();
```

### Issue: "Permission denied" when creating database.sqlite

**Solution:** Run as administrator or create manually
```bash
# PowerShell as Administrator
New-Item -ItemType File -Path "database\database.sqlite" -Force
```

### Issue: Images not loading

**Solution:** 
```bash
# Create storage link
php artisan storage:link

# Check if link exists
Test-Path public\storage
```

Place images in: `storage/app/public/buildings/`
Access via: `http://127.0.0.1:8000/storage/buildings/image.jpg`

### Issue: Port 8000 already in use

**Solution:** Use different port
```bash
php artisan serve --port=8080
```

Then visit: http://127.0.0.1:8080

### Issue: Building clicks don't show details

**Solution:** Check browser console (F12)
1. Open browser Developer Tools (F12)
2. Click a building
3. Look for errors in Console tab
4. Common causes:
   - SVG ID not mapped (check `svgToDbName` in map.blade.php)
   - Building not in database (check with tinker)
   - API endpoint error (check Network tab)

---

## Database Maintenance

### View All Tables

```bash
php artisan tinker

# Show all tables
DB::select("SELECT name FROM sqlite_master WHERE type='table'");

# Count records
App\Models\Building::count();
App\Models\Office::count();
App\Models\Service::count();
```

### Clear All Data and Reseed

```bash
# ⚠️ WARNING: This deletes ALL data
php artisan migrate:fresh --seed
```

### Add More Building Data

1. Edit `database/seeders/campus_data.csv`
2. Add rows in format: `ID,Building,Office,Floor,Head,Position`
3. Re-run seeder:
   ```bash
   php artisan db:seed --class=SmartCampusSeeder
   ```

### Backup Database

```bash
# Copy SQLite file
copy database\database.sqlite database\backups\database_backup_20251124.sqlite
```

### Restore Database

```bash
# Restore from backup
copy database\backups\database_backup_20251124.sqlite database\database.sqlite
```

---

## Production Deployment

### Prepare for Production

```bash
# Optimize configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment to production in .env
APP_ENV=production
APP_DEBUG=false
```

### Security Checklist

- [ ] Change admin password from default
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Generate new `APP_KEY`
- [ ] Use strong database credentials
- [ ] Enable HTTPS
- [ ] Restrict admin panel access
- [ ] Regular database backups

---

## Additional Commands

### Cache Management

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear compiled files
php artisan clear-compiled
```

### View Routes

```bash
# List all routes
php artisan route:list

# Filter specific routes
php artisan route:list --path=api
php artisan route:list --path=admin
```

### Database Queries

```bash
# Open Tinker
php artisan tinker

# Useful queries
App\Models\Building::with('offices')->get();
App\Models\Office::where('building_id', 1)->get();
App\Models\Service::all();
App\Models\Admin::first();
```

---

## Quick Reference Card

```bash
# Start fresh
php artisan migrate:fresh --seed

# Run single seeder
php artisan db:seed --class=SmartCampusSeeder

# Start server
php artisan serve

# Check data
php artisan tinker
App\Models\Building::count()

# Clear caches
php artisan cache:clear

# View routes
php artisan route:list
```

---

## Getting Help

### Log Files
Check Laravel logs for errors:
- Location: `storage/logs/laravel.log`
- View latest: `Get-Content storage\logs\laravel.log -Tail 50`

### Enable Debug Mode
In `.env`:
```env
APP_DEBUG=true
```

### Database Errors
Use Tinker to test:
```bash
php artisan tinker
DB::connection()->getPdo();  # Test database connection
```

---

## Summary

**Minimum steps to get running:**

1. `composer install`
2. `copy .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `php artisan db:seed --class=SmartCampusSeeder`
6. `php artisan serve`
7. Visit http://127.0.0.1:8000/map

**Default credentials:**
- Admin: `admin` / `password`

Happy coding! 🎉
