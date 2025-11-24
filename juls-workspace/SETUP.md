# Juls Workspace - Quick Setup

## 📦 What's Included

This folder contains everything needed to work on the SKSU Campus Kiosk project:

- `README.md` - Full project overview
- `COLLABORATOR_GUIDE.md` - Step-by-step setup guide
- `campus_data.csv` - Campus buildings and offices data
- `SmartCampusSeeder.php` - Database seeder for importing CSV data

## 🚀 Quick Start

1. **Clone the repository:**
   ```bash
   git clone https://github.com/christiankethaguacito-code/kiosk.git
   cd kiosk
   ```

2. **Checkout the juls branch:**
   ```bash
   git checkout juls
   ```

3. **Read the guides:**
   - Start with `COLLABORATOR_GUIDE.md`
   - Reference `README.md` for project structure

4. **Import data:**
   ```bash
   php artisan db:seed --class=SmartCampusSeeder
   ```

5. **Start working:**
   ```bash
   php artisan serve
   ```
   Visit: http://127.0.0.1:8000/map

## 📋 Key Files to Modify

- **Map Interface**: `resources/views/kiosk/map.blade.php`
- **API Routes**: `routes/api.php`
- **Controllers**: `app/Http/Controllers/KioskController.php`
- **Models**: `app/Models/` (Building, Office, Service)
- **Data Import**: `database/seeders/campus_data.csv`

## 🎯 Current Status

✅ Interactive SVG campus map  
✅ Building details sidebar  
✅ CSV data import system  
✅ 13 buildings with office data  
⏳ 29 buildings need data  
⏳ Building photos needed  

Happy coding! 🎉
