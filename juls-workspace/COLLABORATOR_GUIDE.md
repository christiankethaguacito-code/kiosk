# COLLABORATOR QUICK START GUIDE

## 🎯 What You Need to Know

### Project Overview
This is a Laravel-based campus kiosk system for SKSU with an interactive SVG map showing buildings, offices, and services.

### Setup (One-Time)
```bash
# 1. Clone and checkout the feature branch
git clone <repository-url>
cd PatisoyFinal/Navi
git checkout putay

# 2. Install dependencies (if needed)
composer install

# 3. Set up database (already exists)
# Database file: database/database.sqlite

# 4. Import campus data
php artisan db:seed --class=SmartCampusSeeder

# 5. Start server
php artisan serve
```

### Daily Workflow
```bash
cd Navi
php artisan serve
# Visit: http://127.0.0.1:8000/map
```

---

## 📂 Where to Find Things

### 🗺️ Map Interface (Main Feature)
**File**: `resources/views/kiosk/map.blade.php` (2500+ lines)
- Interactive SVG campus map
- Building click handlers (line ~1195)
- Sidebar toggle logic (line ~680)
- Building details display (line ~1280)

### 🔌 API Endpoints
**File**: `routes/api.php`
- `GET /api/buildings` - List all buildings with offices
- `GET /api/buildings/{id}` - Get building details with offices and services

### 💾 Database Seeder
**File**: `database/seeders/SmartCampusSeeder.php`
- Imports data from `campus_data.csv`
- Handles Excel merged cells (forward-fill logic)

**Data File**: `database/seeders/campus_data.csv`
- 42 buildings with offices, heads, and services
- Format: ID, Building, Office, Floor, Head, Position

### 🎨 Controllers
**File**: `app/Http/Controllers/KioskController.php`
- `idle()` - Welcome screen
- `map()` - Interactive map
- `building()` - Building details page
- `office()` - Office details page

### 🏢 Database Models
**Files**: `app/Models/`
- `Building.php` - Building model with offices relationship
- `Office.php` - Office model with building and services
- `Service.php` - Service model with office relationship

---

## 🎨 Current Features

### ✅ Completed
- [x] Interactive SVG map with 42 clickable buildings
- [x] Dynamic sidebar that shows building details
- [x] CSV import system (SmartCampusSeeder)
- [x] API endpoints for building data
- [x] Navigation path display when building clicked
- [x] Image placeholder system for buildings without photos
- [x] Fallback message for buildings without data
- [x] Compact office cards with heads, titles, services

### ⏳ Needs Work
- [ ] Add data for 29 buildings without offices (see CSV rows with empty offices)
- [ ] Upload building photos to `storage/app/public/buildings/`
- [ ] Add building descriptions
- [ ] Test all 42 building clicks
- [ ] Mobile responsive design improvements

---

## 🔨 Common Tasks

### Add a New Building
1. Edit `database/seeders/campus_data.csv`
2. Add row: `ID,Building Name,Office Name,Floor,Head Name,Title`
3. Run: `php artisan db:seed --class=SmartCampusSeeder`

### Upload Building Image
1. Place image in `storage/app/public/buildings/`
2. Name it: `building-name.jpg` (lowercase, hyphens)
3. Update database: `UPDATE buildings SET image_path = 'buildings/building-name.jpg' WHERE id = X`
4. Run: `php artisan storage:link` (if not done yet)

### Test API Endpoints
```bash
# List all buildings
curl http://127.0.0.1:8000/api/buildings

# Get building details
curl http://127.0.0.1:8000/api/buildings/1
```

### Debug Building Clicks
Open browser console (F12) when clicking buildings - it logs:
- `Building not found in database: <name>` - Building needs to be added
- `No database mapping for SVG ID: <id>` - SVG ID needs mapping in `map.blade.php`

---

## 📊 Database Schema

```sql
-- buildings table
id, name, image_path, map_x, map_y

-- offices table  
id, building_id, name, floor_number, head_name, head_title

-- services table
id, office_id, description
```

### Current Data Count
- **13 buildings** with office data
- **45 offices** imported
- **45 services** imported
- **29 buildings** need data (show "Coming Soon" message)

---

## 🐛 Known Issues

1. **Buildings without data show "Coming Soon"**
   - Solution: Add data to `campus_data.csv` and re-seed

2. **Some SVG buildings don't respond**
   - Check `svgToDbName` mapping in `map.blade.php` (line ~1152)

3. **Images not showing**
   - Run: `php artisan storage:link`
   - Check file exists in `storage/app/public/buildings/`

---

## 🚀 Git Workflow

```bash
# Pull latest changes
git pull origin putay

# Make changes, then commit
git add .
git commit -m "Your descriptive message"
git push origin putay

# When feature is done, create pull request to merge into 'itot'
```

---

## 💡 Tips

- **Always work in `Navi/` directory**, not `front-end/`
- **Test map clicks** after any JavaScript changes
- **Check browser console** (F12) for errors
- **Use `php artisan route:list`** to see all routes
- **Database location**: `Navi/database/database.sqlite`
- **View compiled changes**: Refresh browser (Ctrl+F5)

---

## 📞 Questions?

Check these files for context:
1. `README.md` - Project overview
2. `PHASE1_TESTING_GUIDE.md` - Testing documentation (in Navi/)
3. `routes/web.php` - All available routes
4. `resources/views/kiosk/map.blade.php` - Main map interface

Happy coding! 🎉
