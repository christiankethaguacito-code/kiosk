# SKSU Campus Kiosk - Quick Reference Card

## 🎯 Deployment Options

### 🚀 TRUE PLUG-AND-PLAY (After first-time setup)
```
Just plug USB - AUTOMATIC installation!
```

### 📦 First Time Setup (Run ONCE)
```bash
bash AUTORUN.sh  # Choose Option 1
```

### 🔧 Manual Installation
```bash
bash AUTORUN.sh  # Choose Option 2
```

## 🎮 Control Commands
```bash
~/kiosk-start.sh      # Start the kiosk
~/kiosk-stop.sh       # Stop the kiosk
~/kiosk-restart.sh    # Restart the kiosk
~/kiosk-update.sh     # Update from USB
```

## 🌐 Access Points
- **Kiosk Interface**: http://localhost
- **Admin Panel**: http://localhost (Login button top-right)

## 🔐 Default Credentials
- **Username**: admin
- **Password**: admin123

## 📁 Important Locations
- **Project**: `/home/pi/sksu-kiosk/`
- **Logs**: `/home/pi/sksu-kiosk/storage/logs/`
- **Database**: `/home/pi/sksu-kiosk/database/database.sqlite`

## 🔧 Troubleshooting
```bash
# Check services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm

# View logs
tail -f /home/pi/sksu-kiosk/storage/logs/laravel.log

# Check database
ls -lh /home/pi/sksu-kiosk/database/database.sqlite

# Restart everything
~/kiosk-restart.sh
```

## 🔄 Update Process
1. Plug in USB with latest files
2. Run: `~/kiosk-update.sh`
3. Wait for completion
4. System auto-restarts

## 📞 Emergency Commands
```bash
# Kill kiosk
pkill chromium

# Restart services
sudo systemctl restart nginx php8.2-fpm

# Backup database
cp /home/pi/sksu-kiosk/database/database.sqlite ~/backup_$(date +%Y%m%d).sqlite

# Restore database
cp ~/backup_20241201.sqlite /home/pi/sksu-kiosk/database/database.sqlite
sudo chmod 664 /home/pi/sksu-kiosk/database/database.sqlite
sudo chown www-data:www-data /home/pi/sksu-kiosk/database/database.sqlite

# Check database integrity
sqlite3 /home/pi/sksu-kiosk/database/database.sqlite "PRAGMA integrity_check;"
```

---
**SKSU Access Campus Kiosk v1.0.0**
