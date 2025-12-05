# 🎯 PLUG-AND-PLAY QUICK START

## ⚡ FASTEST WAY - Just 3 Steps!

### **1️⃣ FIRST TIME SETUP** (10 seconds - Do once per Raspberry Pi)

```
Plug USB → Open Terminal → Type this:

cd /media/pi/*/Navi && bash AUTORUN.sh

Press: 1

✅ Setup complete!
```

---

### **2️⃣ EVERY TIME AFTER** (Zero commands!)

```
🔌 Plug USB into Raspberry Pi

📱 Popup appears: "Installing..."

☕ Wait 5-10 minutes (grab coffee!)

🔄 System reboots automatically

🖥️ Kiosk starts - DONE!
```

---

## 📊 Visual Flow

```
╔════════════════════════════════════════════════════════╗
║  FIRST TIME (Setup - Once per Raspberry Pi)           ║
╚════════════════════════════════════════════════════════╝

    YOU                          SYSTEM
     │                              │
     ├──Plug USB ────────────────►  │
     │                              │
     ├──Open Terminal ───────────►  │
     │                              │
     ├──Type: bash AUTORUN.sh ───►  │
     │                              ├─Show menu
     │                              │
     ├──Press: 1 ────────────────►  │
     │                              ├─Install udev rule
     │                              ├─Install handler
     │                              ├─Install packages
     │                              │
     │                         ✅ DONE! Setup complete
     │                              │
     
     
╔════════════════════════════════════════════════════════╗
║  EVERY TIME AFTER (Automatic - Zero commands!)        ║
╚════════════════════════════════════════════════════════╝

    YOU                          SYSTEM
     │                              │
     ├──Plug USB ────────────────►  │
     │                              ├─Detect USB
     │                              ├─Mount USB
     │                              ├─Find install.sh
     │                              ├─Show popup: "Installing..."
     │                              │
     │  (Walk away!)                ├─Install packages
     │                              ├─Copy files
     │                              ├─Setup database
     │                              ├─Configure services
     │                              ├─Optimize Laravel
     │                              │
     │                              ├─Show popup: "Complete!"
     │                              ├─Countdown: 10...9...8...
     │                              │
     │                              ├─Reboot
     │                              │
     │  (Come back!)                ├─Start kiosk
     │                              │
     │                         🖥️ KIOSK RUNNING!
     │                              │
```

---

## 🎬 What You'll See

### **First Time Setup:**
```
$ cd /media/pi/*/Navi && bash AUTORUN.sh

╔════════════════════════════════════════════════════════╗
║   SKSU Campus Kiosk - Installation Launcher           ║
╚════════════════════════════════════════════════════════╝

Choose installation mode:

1) 🚀 AUTOMATIC - Plug-and-Play (Recommended)
   → Setup auto-run, then just plug USB anytime

2) 📦 MANUAL - Install Now
   → Run installation immediately

Select option (1 or 2): 1

Setting up automatic plug-and-play mode...

╔════════════════════════════════════════════════════════╗
║   SKSU Campus Kiosk - Auto-Run Setup                  ║
╚════════════════════════════════════════════════════════╝

This will configure your Raspberry Pi to automatically
install the kiosk when you plug in the USB drive.

⚠️  You only need to run this ONCE per Raspberry Pi

Continue? (y/N): y

📦 Installing required packages...
✅ Packages installed

📝 Creating USB handler script...
✅ Handler script created

⚙️  Creating udev rule...
✅ Udev rule created

🔄 Reloading udev rules...
✅ Udev rules reloaded

╔════════════════════════════════════════════════════════╗
║   SETUP COMPLETE!                                      ║
╚════════════════════════════════════════════════════════╝

✅ Your Raspberry Pi is now ready for plug-and-play!

Next steps:
1. Safely eject this USB drive
2. Copy the entire Navi folder to a USB drive
3. Plug the USB into this Raspberry Pi
4. Installation will start AUTOMATICALLY!

🎉 No terminal commands needed - just plug and go!
```

### **Automatic Installation (After Setup):**
```
[You plug USB]

┌─────────────────────────────────────────────┐
│  SKSU Kiosk                              ℹ️  │
├─────────────────────────────────────────────┤
│  USB Drive Detected!                        │
│                                             │
│  Automatic installation starting...         │
│                                             │
│  Please wait 5-10 minutes.                  │
│                                             │
│  DO NOT remove USB drive.                   │
└─────────────────────────────────────────────┘

[5-10 minutes pass...]

┌─────────────────────────────────────────────┐
│  Installation Complete                   ✅  │
├─────────────────────────────────────────────┤
│  SKSU Campus Kiosk installed successfully!  │
│                                             │
│  System will reboot in 10 seconds...        │
│                                             │
│  10... 9... 8... 7... 6...                  │
└─────────────────────────────────────────────┘

[System reboots]

[Kiosk appears in fullscreen - DONE!]
```

---

## ✅ Checklist

### **Before First Use:**
- [ ] Raspberry Pi 5 with Raspberry Pi OS installed
- [ ] Internet connection available
- [ ] USB drive with Navi folder
- [ ] All files present (install.sh, setup-autorun.sh, etc.)

### **After First Setup:**
- [ ] Run setup once: `bash AUTORUN.sh` → Press `1`
- [ ] Verify udev rule: `ls /etc/udev/rules.d/99-kiosk-usb.rules`
- [ ] Verify handler: `ls /usr/local/bin/kiosk-usb-handler.sh`
- [ ] Test by plugging USB again

### **For Each Installation:**
- [ ] Plug USB
- [ ] Wait for popup
- [ ] Wait 5-10 minutes
- [ ] System reboots
- [ ] Kiosk appears
- [ ] Test touch screen
- [ ] Test admin login

---

## 🔧 Troubleshooting

### **Popup doesn't appear after plugging USB:**
```bash
# Check if auto-run is setup
ls /etc/udev/rules.d/99-kiosk-usb.rules

# If missing, run setup again
cd /media/pi/*/Navi && bash AUTORUN.sh
# Choose option 1
```

### **Manual installation if auto-run fails:**
```bash
cd /media/pi/*/Navi
bash AUTORUN.sh
# Choose option 2
```

### **Check installation log:**
```bash
# On USB drive
cat /media/pi/*/Navi/auto-install.log

# Or after installation
cat /home/pi/sksu-kiosk/deployment.log
```

---

## 🎊 SUCCESS!

You now have:
- ✅ **TRUE plug-and-play** deployment
- ✅ **ZERO typing** after first setup
- ✅ **Automatic** installation
- ✅ **Popup notifications**
- ✅ **Auto-reboot** when done
- ✅ **Kiosk starts** on boot

**Just plug USB and walk away!** 🚀

---

**SKSU Campus Kiosk v2.0**  
*True Plug-and-Play Edition*
