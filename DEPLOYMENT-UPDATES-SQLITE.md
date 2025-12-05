# 🔄 Deployment Scripts Updated - SQLite Migration

## ✅ Summary of Changes (December 1, 2025)

All deployment scripts have been updated to use **SQLite** instead of MariaDB/MySQL for optimal Raspberry Pi 5 kiosk performance.

---

## 📋 Files Updated

### 1. `.env.example`
**Changes:**
- ✅ Changed `DB_CONNECTION` from `mysql` to `sqlite`
- ✅ Commented out MySQL-specific variables (DB_HOST, DB_PORT, etc.)
- ✅ Changed `SESSION_DRIVER` from `database` to `file` for better performance

### 2. `deploy-kiosk.sh` (Main deployment script)
**Major Changes:**
- ✅ **Removed**: MariaDB installation and setup (Steps 5-6)
- ✅ **Added**: SQLite database file copy and permissions setup
- ✅ **Updated**: Environment configuration for SQLite
- ✅ **Added**: Database size check before running migrations
- ✅ **Added**: Laravel optimization commands (config:cache, route:cache, view:cache)
- ✅ **Added**: Storage link creation

**New Logic:**
- If `database.sqlite` exists on USB → Copy it
- If not → Create empty file
- Check file size before seeding (only seed if empty)
- Set proper permissions (664, www-data:www-data)

### 3. `update-from-usb.sh`
**Changes:**
- ✅ Replaced MySQL backup command with SQLite file copy
- ✅ Added database update prompt (asks before overwriting)
- ✅ Excludes `database.sqlite` from rsync (prevents accidental overwrite)
- ✅ Removed database migration step (only updates code)

### 4. `prepare-usb.bat`
**Changes:**
- ✅ Updated verification to check for `database\database.sqlite` instead of SQL dump
- ✅ Changed error handling to warning (allows deployment with empty DB)

### 5. `README-DEPLOYMENT.md`
**Comprehensive Updates:**
- ✅ Added "Database Architecture" section explaining SQLite benefits
- ✅ Updated "Installed Components" section (SQLite instead of MariaDB)
- ✅ Removed MySQL credentials, added SQLite file path
- ✅ Updated environment configuration example
- ✅ Added SQLite-specific troubleshooting commands
- ✅ Added backup/restore procedures for SQLite
- ✅ Added database integrity check commands

### 6. `QUICK-REFERENCE.md`
**Changes:**
- ✅ Updated database location to file path
- ✅ Removed MariaDB status checks
- ✅ Added SQLite backup/restore commands
- ✅ Added database integrity check command
- ✅ Updated emergency commands for SQLite

---

## 💾 SQLite Advantages for Raspberry Pi Kiosk

### Why SQLite is Perfect for This Project:

1. **⚡ Zero Server Overhead**
   - No database server process consuming RAM
   - No network sockets or TCP/IP overhead
   - Direct file I/O is faster on local system

2. **🎯 Embedded System Optimized**
   - Designed for embedded devices like Raspberry Pi
   - Low memory footprint (perfect for 4GB/8GB Pi)
   - Single file database = simple deployment

3. **📦 Easy Backup & Restore**
   - Backup = copy one file
   - Restore = copy file back
   - No mysqldump or complex procedures

4. **🔧 Zero Configuration**
   - No database server setup
   - No user management
   - No network configuration
   - Works immediately after file copy

5. **🛡️ Built-in Reliability**
   - ACID compliant
   - Atomic commits
   - Crash recovery
   - File locking prevents corruption

6. **💰 Resource Efficient**
   - Uses ~3-5MB RAM (vs 100-200MB for MySQL)
   - No startup time
   - No connection pooling needed
   - Perfect for 24/7 kiosk operation

---

## 🚀 New Deployment Flow

### Before (MySQL/MariaDB):
1. Install MySQL server package
2. Start MySQL service
3. Create database
4. Create user with password
5. Grant privileges
6. Import SQL dump
7. Configure Laravel .env with credentials
8. Enable MySQL service on boot

### After (SQLite):
1. Copy `database.sqlite` file
2. Set permissions (chmod 664)
3. Set owner (www-data:www-data)
4. Configure Laravel .env (just file path)
5. **Done!** ✅

---

## 📊 Performance Comparison

| Aspect | MySQL/MariaDB | SQLite |
|--------|---------------|--------|
| **RAM Usage** | 100-200 MB | 3-5 MB |
| **CPU Usage** | Constant process | On-demand |
| **Startup Time** | 2-5 seconds | Instant |
| **Configuration** | Complex | None |
| **Backup** | mysqldump | File copy |
| **Restore** | mysql import | File copy |
| **Updates** | SQL commands | File replacement |
| **Ideal For** | Multi-user, network | Single-app, embedded |

---

## 🔐 Database Security

### File Permissions:
```bash
# Correct permissions for database.sqlite
-rw-rw-r-- 1 www-data www-data 155648 Dec 1 database.sqlite

# Owner: www-data (PHP-FPM process)
# Group: www-data
# Permissions: 664 (rw-rw-r--)
```

### Why These Permissions:
- **Owner (www-data)**: PHP-FPM needs read/write access
- **Group (www-data)**: Allows backup scripts to read
- **Others (read-only)**: Prevents unauthorized modifications
- **Directory (755)**: database/ folder is readable but not writable by others

---

## 🧪 Testing Checklist

Before deploying to Raspberry Pi, verify:

- [ ] `database/database.sqlite` exists and has data (>100KB)
- [ ] `.env.example` has `DB_CONNECTION=sqlite`
- [ ] `deploy-kiosk.sh` doesn't mention MySQL/MariaDB
- [ ] `update-from-usb.sh` uses file copy for database
- [ ] All documentation updated to SQLite
- [ ] USB preparation script checks for `.sqlite` file
- [ ] Backup/restore commands use `cp` instead of `mysqldump`

---

## 📝 Manual Verification Commands

Run these on Raspberry Pi after deployment:

```bash
# Check database file exists
ls -lh /home/pi/sksu-kiosk/database/database.sqlite

# Verify permissions
stat -c "%a %U:%G %n" /home/pi/sksu-kiosk/database/database.sqlite
# Should show: 664 www-data:www-data /home/pi/sksu-kiosk/database/database.sqlite

# Test database integrity
sqlite3 /home/pi/sksu-kiosk/database/database.sqlite "PRAGMA integrity_check;"
# Should return: ok

# Count records
sqlite3 /home/pi/sksu-kiosk/database/database.sqlite "SELECT COUNT(*) FROM buildings;"
# Should return: number of buildings (e.g., 43)

# Check Laravel can connect
cd /home/pi/sksu-kiosk
php artisan db:show
# Should display SQLite connection info
```

---

## 🎯 Next Steps

1. **Test on Development Machine**:
   - Verify all scripts run without MySQL errors
   - Test database copy and permissions
   - Confirm Laravel connects to SQLite

2. **Prepare USB Drive**:
   - Run `prepare-usb.bat` from Windows
   - Verify `database.sqlite` is included
   - Copy entire Navi folder to USB

3. **Deploy to Raspberry Pi**:
   - Plug USB into Raspberry Pi 5
   - Run `bash AUTORUN.sh`
   - Monitor deployment log
   - Verify kiosk starts automatically

4. **Post-Deployment Verification**:
   - Check all pages load correctly
   - Verify building data appears
   - Test admin login
   - Confirm announcements display

---

## 📞 Support Information

If you encounter issues:

1. **Check deployment log**: `cat deployment.log`
2. **Check Laravel log**: `tail -f storage/logs/laravel.log`
3. **Verify database**: Run manual verification commands above
4. **Check permissions**: `ls -lh database/database.sqlite`

---

**Updated by**: Christian Keth Aguacito  
**Date**: December 1, 2025  
**Version**: 1.0.0 (SQLite Edition)
