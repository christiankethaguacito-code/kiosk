# 🚀 SKSU Campus Kiosk - Setup Guide

## Quick Start (One Command!)

### Windows (PowerShell)
```powershell
# Navigate to project folder
cd path\to\PatisoyFinal\Navi

# Run setup script
.\setup.ps1
```

If you get an error about execution policy, run this first:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

---

## What the Setup Script Does

1. ✅ Checks for PHP installation (XAMPP/WAMP/Laragon)
2. ✅ Installs/checks Composer
3. ✅ Installs PHP dependencies (Laravel packages)
4. ✅ Checks Node.js (optional)
5. ✅ Creates `.env` configuration file
6. ✅ Generates application key
7. ✅ Creates SQLite database
8. ✅ Runs database migrations
9. ✅ Seeds admin user (username: admin, password: password)
10. ✅ Imports 15 buildings with 49 offices and 144 services
11. ✅ Creates storage link for images
12. ✅ Starts the development server

---

## Manual Setup (If Script Fails)

### Prerequisites
- **PHP 8.2+** (via XAMPP, WAMP, or Laragon)
- **Composer** (https://getcomposer.org/)
- **Node.js** (optional, for frontend development)

### Step-by-Step

1. **Install PHP Dependencies**
   ```bash
   composer install
   ```

2. **Setup Environment**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

3. **Create Database**
   ```bash
   # Create file: database/database.sqlite
   php artisan migrate
   ```

4. **Seed Database**
   ```bash
   php artisan db:seed --class=AdminSeeder
   php artisan db:seed --class=JsonBuildingSeeder
   ```

5. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

---

## After Setup

### Access the Application
- **Campus Map**: http://127.0.0.1:8000/map
- **Admin Login**: http://127.0.0.1:8000/login
- **Admin Dashboard**: http://127.0.0.1:8000/admin

### Admin Credentials
- **Username**: `admin`
- **Password**: `password`

### Project Structure
```
Navi/
├── app/                    # Application code
│   ├── Http/Controllers/   # Controllers
│   └── Models/            # Database models
├── database/
│   ├── database.sqlite    # SQLite database
│   └── seeders/           # Data seeders
│       └── buildings_data.json  # Building data
├── resources/
│   └── views/             # Blade templates
│       └── kiosk/         # Kiosk pages
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
└── public/               # Public assets
```

---

## Database Information

### Current Data
- **Buildings**: 15
- **Offices**: 49
- **Services**: 144

### Imported Buildings
1. Administration Building (20 offices, 76 services)
2. QMS Center Building (3 offices, 20 services)
3. Laboratory High School (2 offices)
4. College of Teacher Education (8 offices, 1 service)
5. College of Health Sciences (1 office, 1 service)
6. Colleges of Health Sciences Extension (1 office, 1 service)
7. Graduate School (3 offices, 9 services)
8. Alumni Relationship Office (1 office, 1 service)
9. GS-SBO (1 office, 1 service)
10. University Access Clinic (1 office, 3 services)
11. Office of Student Affairs and Services (3 offices, 21 services)
12. Climate (1 office, 1 service)
13. Technology and Innovation Building (2 offices)
14. University Learning Resource Center (1 office, 9 services)
15. Main Entrance (1 office)

---

## Common Issues

### "PHP not found"
- Install XAMPP from https://www.apachefriends.org/
- Or add PHP to your system PATH

### "Composer not found"
- Download from https://getcomposer.org/download/
- Or the script will auto-install composer.phar locally

### "Port 8000 already in use"
- Stop other Laravel servers
- Or use a different port: `php artisan serve --port=8001`

### "Permission denied" on PowerShell script
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Database errors
- Delete `database/database.sqlite` and run setup again
- Or run: `php artisan migrate:fresh --seed`

---

## Development

### Run Server
```bash
php artisan serve
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Re-import Building Data
```bash
php artisan db:seed --class=JsonBuildingSeeder
```

### Check Routes
```bash
php artisan route:list
```

---

## Git Branches

- **main**: Production-ready code
- **juls**: Current development branch with latest features
- **putay**: Feature branch
- **itot**: Testing branch

---

## Support

For issues or questions:
1. Check the terminal output for detailed error messages
2. Review the logs in `storage/logs/laravel.log`
3. Refer to Laravel documentation: https://laravel.com/docs

---

## Features

### Campus Map (Interactive SVG)
- Click on buildings to see details
- Sidebar with office information
- Services listed per office
- Office head names and titles
- "View Full Details" button for comprehensive view

### Admin Panel
- Manage buildings, offices, and services
- Update building coordinates
- Configure navigation endpoints
- User authentication

### API Endpoints
- `/api/buildings` - List all buildings with offices and services
- `/api/buildings/{id}` - Get specific building details
- `/api/search?q=query` - Search buildings and offices

---

**Last Updated**: November 25, 2025
**Laravel Version**: 11.x
**PHP Version**: 8.2.12
**Database**: SQLite
