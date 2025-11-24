# SKSU Campus Kiosk System

## 📁 Project Structure

```
PatisoyFinal/
├── Navi/                          # Main Laravel Application (WORK HERE)
│   ├── app/                       # Laravel application logic
│   │   ├── Http/Controllers/      # Controllers for routing
│   │   └── Models/                # Database models (Building, Office, Service)
│   ├── database/
│   │   ├── seeders/               # Data seeders (SmartCampusSeeder, campus_data.csv)
│   │   └── database.sqlite        # SQLite database
│   ├── resources/views/           # Blade templates
│   │   └── kiosk/
│   │       ├── idle.blade.php     # Welcome screen (/)
│   │       └── map.blade.php      # Interactive map (/map)
│   ├── routes/
│   │   ├── web.php                # Web routes
│   │   └── api.php                # API endpoints
│   └── public/                    # Public assets
│
├── docs/                          # Documentation files
│   └── Cedana_Magallosa_Manuscript Revision.docx
│
├── database-backups/              # SQL backups
│   └── campus_kiosk.sql
│
├── assets/                        # Source files (Excel, images, etc.)
│   └── BLDG..xlsx
│
├── archived/                      # Old backups and unused files
│   └── BACKUPS/
│
└── front-end/                     # [DEPRECATED] Old React prototype
```

## 🚀 Getting Started

### Main Application
```bash
cd Navi
php artisan serve
```
Visit: http://127.0.0.1:8000/map

### Import Campus Data
```bash
php artisan db:seed --class=SmartCampusSeeder
```

## 🗺️ Key Entry Points

- **`/`** → Welcome/Idle screen (`resources/views/kiosk/idle.blade.php`)
- **`/map`** → Interactive campus map (`resources/views/kiosk/map.blade.php`)
- **`/admin/dashboard`** → Admin panel (requires login)

## 🔧 For Collaborators

### Where to Work
- **Backend Logic**: `Navi/app/Http/Controllers/`
- **Database Models**: `Navi/app/Models/`
- **Frontend Views**: `Navi/resources/views/kiosk/`
- **API Routes**: `Navi/routes/api.php`
- **Web Routes**: `Navi/routes/web.php`
- **Data Import**: `Navi/database/seeders/`

### Database Structure
- **buildings** (id, name, image_path, map_x, map_y)
- **offices** (id, building_id, name, floor_number, head_name, head_title)
- **services** (id, office_id, description)

### Current Features
✅ Interactive SVG campus map with 42 clickable buildings
✅ Dynamic building details sidebar
✅ Navigation path display
✅ CSV data import with SmartCampusSeeder
✅ API endpoints for building/office/service data
✅ Image upload system for buildings
✅ Admin panel for data management

### Branch Information
- **Main branch**: `itot`
- **Current feature branch**: `putay` (Building details sidebar)

## 📦 Dependencies
- PHP 8.2.12 (XAMPP)
- Laravel 11
- SQLite
- Tailwind CSS (CDN)

## 🎯 Next Steps for Collaborators
1. Pull the `putay` branch
2. Review `Navi/resources/views/kiosk/map.blade.php` for map functionality
3. Check `Navi/database/seeders/campus_data.csv` for data structure
4. Use `Navi/routes/api.php` to understand available endpoints
5. Add missing building data to complete the system

## ⚠️ Important Notes
- The `front-end/` folder is deprecated (old React prototype)
- Work only in the `Navi/` directory
- Use `php artisan serve` to run the development server
- Database file: `Navi/database/database.sqlite`
