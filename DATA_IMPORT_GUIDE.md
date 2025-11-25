# 📸 Building Data & Images Import Guide

## 🎯 What You Have
- ✅ Complete list of services, buildings, and offices
- ✅ Pictures for each building

## 📋 Step-by-Step Import Process

### Option 1: Update CSV and Re-import (Recommended)

#### Step 1: Update campus_data.csv
**Location**: `database/seeders/campus_data.csv`

**Format**:
```csv
ID,List of Buildings,List of Offices,Floor,Head of Offices,Position
1,Building Name,Office Name,Floor Number,Head Name,Position Title
```

**Instructions**:
1. Open `database/seeders/campus_data.csv`
2. Add your complete building and office data
3. For buildings without offices, just add the building name row
4. Save the file

**Example**:
```csv
1,Administration Building,ICT Office,,Nasser D. Kalilangan,IT Officer
,,Registrar Office,1st,Dr. Hassanal Abusama,Director
2,University Gymnasium,,,,
3,Parking Space,,,,
```

#### Step 2: Clear and Re-import Data
```bash
# Navigate to project
cd C:\Users\USER\OneDrive\Desktop\PatisoyFinal\Navi

# Clear existing data (OPTIONAL - only if you want fresh start)
C:\xampp\php\php.exe artisan migrate:fresh

# Re-import all data
C:\xampp\php\php.exe artisan db:seed --class=SmartCampusSeeder
```

---

### Option 2: Add Data Through Admin Panel (Easy UI)

#### Step 1: Login to Admin Panel
1. Visit: http://127.0.0.1:8000/login
2. Username: `admin`
3. Password: `password`

#### Step 2: Add Buildings
1. Go to **Buildings** section
2. Click **"Create Building"**
3. Fill in:
   - Building Name
   - Upload building image
4. Click **Save**

#### Step 3: Add Offices to Building
1. Go to **Offices** section
2. Click **"Create Office"**
3. Select building from dropdown
4. Fill in:
   - Office Name
   - Floor Number
   - Head Name
   - Head Title
5. Click **Save**

#### Step 4: Add Services to Office
1. Go to **Services** section
2. Click **"Create Service"**
3. Select office from dropdown
4. Enter service description
5. Click **Save**

---

## 📸 Adding Building Images

### Method 1: Through Admin Panel (Easiest)
1. Login to admin panel
2. Go to **Buildings**
3. Click **Edit** on any building
4. Click **"Choose File"** under Building Image
5. Select your building photo
6. Click **Save**

### Method 2: Manual Upload (Bulk)

#### Step 1: Prepare Images
**Requirements**:
- Format: JPG, PNG, or JPEG
- Recommended size: Max 2MB per image
- Naming: Use lowercase with hyphens (e.g., `administration-building.jpg`)

#### Step 2: Upload to Storage
```bash
# Create buildings folder if it doesn't exist
New-Item -ItemType Directory -Path "storage\app\public\buildings" -Force

# Copy your images there
# Example: Copy-Item "C:\path\to\your\images\*.jpg" -Destination "storage\app\public\buildings\"
```

#### Step 3: Link Storage (First time only)
```bash
C:\xampp\php\php.exe artisan storage:link
```

#### Step 4: Update Database
Open Laravel Tinker:
```bash
C:\xampp\php\php.exe artisan tinker
```

Then run (example):
```php
// Update single building
$building = App\Models\Building::where('name', 'Administration Building')->first();
$building->image_path = 'buildings/administration-building.jpg';
$building->save();

// Or update all at once
App\Models\Building::where('name', 'Administration Building')->update(['image_path' => 'buildings/administration-building.jpg']);
App\Models\Building::where('name', 'University Gymnasium')->update(['image_path' => 'buildings/gymnasium.jpg']);

// Exit tinker
exit
```

---

## 🔄 Quick Bulk Import Script

If you have all images named consistently, create this script:

**File**: `update_building_images.php` (save in project root)

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Building;

$imageMapping = [
    'Administration Building' => 'administration-building.jpg',
    'College of Teacher Education' => 'cte.jpg',
    'University Gymnasium' => 'gymnasium.jpg',
    'Laboratory High School' => 'lhs.jpg',
    'QMS Center Building' => 'qms.jpg',
    // Add all your buildings here...
];

foreach ($imageMapping as $buildingName => $imageName) {
    $building = Building::where('name', $buildingName)->first();
    if ($building) {
        $building->image_path = 'buildings/' . $imageName;
        $building->save();
        echo "✓ Updated: $buildingName\n";
    } else {
        echo "✗ Not found: $buildingName\n";
    }
}

echo "\nDone! Updated " . count($imageMapping) . " buildings.\n";
```

Run it:
```bash
C:\xampp\php\php.exe update_building_images.php
```

---

## 📊 Verify Your Data

### Check Building Count
```bash
C:\xampp\php\php.exe artisan tinker
App\Models\Building::count()
App\Models\Office::count()
App\Models\Service::count()
exit
```

### Check Images on Website
1. Visit: http://127.0.0.1:8000/map
2. Click on any building
3. Verify image appears in sidebar

### Check in Admin Panel
1. Visit: http://127.0.0.1:8000/admin/buildings
2. Verify all buildings listed
3. Check thumbnails display

---

## 🗂️ File Structure

```
Navi/
├── storage/
│   └── app/
│       └── public/
│           └── buildings/          ← Your building images go here
│               ├── administration-building.jpg
│               ├── cte.jpg
│               ├── gymnasium.jpg
│               └── ...
├── database/
│   └── seeders/
│       └── campus_data.csv         ← Your complete data list
└── public/
    └── storage/                    ← Symlink (created by storage:link)
        └── buildings/              ← Accessible via web
```

---

## 🆘 Troubleshooting

### Images Not Showing
```bash
# Re-create storage link
C:\xampp\php\php.exe artisan storage:link

# Check if images exist
Test-Path storage\app\public\buildings\your-image.jpg

# Check database
C:\xampp\php\php.exe artisan tinker
App\Models\Building::where('image_path', '!=', null)->get(['name', 'image_path'])
```

### CSV Import Issues
```bash
# Check CSV format
Get-Content database\seeders\campus_data.csv | Select-Object -First 5

# Re-import with fresh database
C:\xampp\php\php.exe artisan migrate:fresh
C:\xampp\php\php.exe artisan db:seed --class=AdminSeeder
C:\xampp\php\php.exe artisan db:seed --class=SmartCampusSeeder
```

### Building Not Found on Map
1. Check if SVG ID matches in `map.blade.php` (line ~1152)
2. Verify `svgToDbName` mapping
3. Ensure building name in database matches mapping

---

## 📝 Quick Checklist

- [ ] Updated `campus_data.csv` with complete data
- [ ] Re-imported data using SmartCampusSeeder
- [ ] Created `storage/app/public/buildings/` folder
- [ ] Copied all building images to storage folder
- [ ] Ran `php artisan storage:link`
- [ ] Updated image_path in database
- [ ] Tested on website (http://127.0.0.1:8000/map)
- [ ] Verified in admin panel
- [ ] Committed changes to git

---

## 🚀 Next Steps

After importing all data:

1. **Test the Map**:
   - Click each building marker
   - Verify building details appear
   - Check that images load properly

2. **Update Admin Panel**:
   - Add any missing services
   - Update office information
   - Add building descriptions

3. **Commit to Git**:
   ```bash
   git add .
   git commit -m "Add complete building data with images"
   git push origin juls
   ```

---

## 💡 Tips

- **Image Naming**: Use consistent naming (lowercase, hyphens)
- **Image Size**: Optimize images before upload (compress to ~500KB)
- **Backup**: Always backup `database.sqlite` before major imports
- **Test First**: Import a few buildings first, test, then do all
- **CSV Format**: Keep header row, use comma separator, quote text with commas

---

Need help? Check:
- `ITOT.md` - Full setup instructions
- `COLLABORATOR_GUIDE.md` - Development guide
- Admin Panel → Dashboard for statistics
