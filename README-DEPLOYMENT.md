# SKSU Campus Kiosk - Raspberry Pi 5 Deployment Guide

## 🚀 Quick Start - Plug & Play Installation

This system is designed for **automatic deployment** on Raspberry Pi 5 with minimal user interaction.

### Prerequisites
- Raspberry Pi 5 with Raspberry Pi OS (Debian-based Linux)
- Internet connection
- USB drive with this project
- Chromium browser (pre-installed on Raspberry Pi OS)

### 💾 Database Architecture
This kiosk system uses **SQLite** for optimal performance:
- ✅ **No database server required** - saves RAM and CPU resources
- ✅ **Single file database** - easy backup and restore (`database.sqlite`)
- ✅ **Zero configuration** - works out of the box
- ✅ **Perfect for embedded systems** - ideal for Raspberry Pi
- ✅ **No network overhead** - faster response times

---

## 📦 Installation Methods

### Method 1: 🚀 TRUE PLUG-AND-PLAY (Recommended - ZERO typing!)

**First Time Setup (Run ONCE per Raspberry Pi):**
1. Plug USB into Raspberry Pi
2. Open terminal and run:
   ```bash
   cd /media/pi/YOUR_USB/Navi
   bash AUTORUN.sh
   ```
3. Choose option **1** (Automatic mode)
4. Setup completes in 10 seconds

**After Setup - Forever Automatic:**
1. **Just plug USB** into Raspberry Pi
2. **Installation starts AUTOMATICALLY** (popup appears)
3. **Wait 5-10 minutes** (progress shown)
4. **System auto-reboots** when done
5. **Kiosk starts automatically!**

✨ **No terminal, no typing, no commands - JUST PLUG USB!** ✨

---

### Method 2: Manual Installation (If auto-run not setup)
1. Plug USB into Raspberry Pi
2. Open terminal:
   ```bash
   cd /media/pi/YOUR_USB/Navi
   bash AUTORUN.sh
   ```
3. Choose option **2** (Manual mode)
4. Wait for installation to complete

---

## 🎮 Management Commands

After installation, use these commands from the terminal:

### Start the Kiosk
```bash
~/kiosk-start.sh
```

### Stop the Kiosk
```bash
~/kiosk-stop.sh
```

### Restart the Kiosk
```bash
~/kiosk-restart.sh
```

### Update from USB
```bash
~/kiosk-update.sh
```

---

## 📁 Installation Details

### Installed Components
- ✅ **PHP 8.2** with SQLite extension
- ✅ **Nginx** web server
- ✅ **SQLite3** database
- ✅ **Composer** dependency manager
- ✅ **Chromium** browser in kiosk mode
- ✅ **Laravel** application with all dependencies

### Project Location
```
/home/pi/sksu-kiosk/
```

### Database Configuration
- **Type**: SQLite (embedded database)
- **File**: `/home/pi/sksu-kiosk/database/database.sqlite`
- **No server required** - runs in the same process as the application

### Web Access
- **URL**: http://localhost
- **Port**: 80

---

## 🔧 Configuration

### Environment File
Located at: `/home/pi/sksu-kiosk/.env`

Key configurations:
```env
APP_NAME="SKSU Campus Kiosk"
APP_URL=http://localhost
DB_CONNECTION=sqlite
DB_DATABASE=/home/pi/sksu-kiosk/database/database.sqlite
SESSION_DRIVER=file
```

### Kiosk Mode Settings
- **Auto-start**: Enabled on boot
- **Screen blanking**: Disabled
- **Cursor**: Hidden
- **Chromium**: Fullscreen kiosk mode

---

## 🔄 Updating the System

### From USB Drive
1. Plug in USB with updated files
2. Run update script:
   ```bash
   ~/kiosk-update.sh
   ```

### Manual Update
```bash
cd /home/pi/sksu-kiosk
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
sudo systemctl restart nginx
```

---

## 🛠️ Troubleshooting

### Kiosk Not Starting
```bash
# Check service status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm

# Restart services
~/kiosk-restart.sh
```

### Database Connection Error
```bash
# Check database file permissions
ls -lh /home/pi/sksu-kiosk/database/database.sqlite

# Fix permissions if needed
sudo chmod 664 /home/pi/sksu-kiosk/database/database.sqlite
sudo chown www-data:www-data /home/pi/sksu-kiosk/database/database.sqlite

# Verify database integrity
sqlite3 /home/pi/sksu-kiosk/database/database.sqlite "PRAGMA integrity_check;"

# Check table count
sqlite3 /home/pi/sksu-kiosk/database/database.sqlite "SELECT COUNT(*) FROM buildings;"
```

### Backup and Restore Database
```bash
# Backup database
cp /home/pi/sksu-kiosk/database/database.sqlite ~/kiosk_backup_$(date +%Y%m%d).sqlite

# Restore database
cp ~/kiosk_backup_20241201.sqlite /home/pi/sksu-kiosk/database/database.sqlite
sudo chmod 664 /home/pi/sksu-kiosk/database/database.sqlite
sudo chown www-data:www-data /home/pi/sksu-kiosk/database/database.sqlite
```

### Chromium Not Opening
```bash
# Kill existing processes
pkill chromium

# Restart kiosk
~/kiosk-start.sh
```

### Check Logs
```bash
# Nginx error log
sudo tail -f /var/log/nginx/error.log

# PHP error log
sudo tail -f /var/log/php8.2-fpm.log

# Laravel log
tail -f /home/pi/sksu-kiosk/storage/logs/laravel.log

# Deployment log
cat /media/pi/USB_DRIVE_NAME/deployment.log
```

---

## 🔐 Security Notes

### Default Credentials
**Admin Login**:
- Username: `admin`
- Password: `admin123`

**⚠️ IMPORTANT**: Change these credentials after first login!

### Database Access
```bash
# Access MySQL
sudo mysql -u kiosk_user -pkiosk_password_2025 campus_kiosk
```

---

## 🎯 Kiosk Features

### Auto-Start on Boot
- System automatically boots into kiosk mode
- Chromium opens in fullscreen
- No user interaction needed

### Screen Settings
- Screen blanking disabled
- DPMS power saving disabled
- Cursor hidden after 0.1s of inactivity

### Browser Settings
- No error dialogs
- No crash recovery
- No translation prompts
- No update notifications
- Fullscreen mode
- No navigation bars

---

## 📊 System Requirements

### Minimum Requirements
- Raspberry Pi 5 (4GB RAM recommended)
- 32GB microSD card
- Raspberry Pi OS (64-bit)
- Internet connection for initial setup

### Recommended Setup
- Raspberry Pi 5 with 8GB RAM
- 64GB+ microSD card (Class 10 or UHS-I)
- Wired Ethernet connection
- Touch screen display

---

## 📞 Support

### Project Information
- **Project**: SKSU Access Campus Kiosk
- **Version**: 1.0.0
- **Researchers**: Hannah Mae V. Magallosa, Sam Jones L. Cedana
- **Adviser**: Charity L. Oria, DEng
- **Institution**: Sultan Kudarat State University

### Files Included
- `deploy-kiosk.sh` - Main deployment script
- `AUTORUN.sh` - Quick start script
- `README-DEPLOYMENT.md` - This file
- `database-backups/` - Database backup files
- `public/` - Web accessible files
- Laravel application files

---

## 📝 Post-Installation Checklist

- [ ] System rebooted successfully
- [ ] Kiosk mode started automatically
- [ ] Web interface accessible at http://localhost
- [ ] Touch screen responsive
- [ ] All building data loaded
- [ ] Images displaying correctly
- [ ] Admin login functional
- [ ] Database populated with correct data
- [ ] Announcements working
- [ ] Map interactive and responsive

---

## 🎨 Customization

### Change Kiosk URL
Edit `/home/pi/start-kiosk.sh`:
```bash
--app=http://your-custom-url/
```

### Adjust Screen Timeout
Edit `/home/pi/start-kiosk.sh`:
```bash
# Change idle time for cursor hide
unclutter -idle 5 -root &
```

### Disable Auto-Start
```bash
rm /home/pi/.config/autostart/kiosk.desktop
```

---

## ✅ Deployment Checklist

### Pre-Deployment
- [ ] USB drive formatted (FAT32/exFAT)
- [ ] All project files copied to USB
- [ ] Database backup included
- [ ] Raspberry Pi 5 connected to internet
- [ ] Display/touch screen connected

### During Deployment
- [ ] Run AUTORUN.sh script
- [ ] Monitor installation progress
- [ ] Note any errors in deployment.log
- [ ] Wait for completion message

### Post-Deployment
- [ ] Reboot system
- [ ] Verify auto-start
- [ ] Test touch interaction
- [ ] Check all features
- [ ] Change default passwords

---

## 🔄 Backup & Restore

### Backup Database
```bash
mysqldump -u kiosk_user -pkiosk_password_2025 campus_kiosk > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
mysql -u kiosk_user -pkiosk_password_2025 campus_kiosk < backup_file.sql
```

### Backup Entire Project
```bash
sudo tar -czf sksu-kiosk-backup_$(date +%Y%m%d).tar.gz /home/pi/sksu-kiosk/
```

---

**End of Deployment Guide**
