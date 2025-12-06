#!/bin/bash
#############################################################
# SKSU Campus Kiosk - Error Log Viewer
# Quick view of deployment errors
#############################################################

echo "╔════════════════════════════════════════════════════════╗"
echo "║   SKSU Campus Kiosk - Error Log Viewer                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Find error logs
USER_HOME="${HOME:-/home/pi}"
ERROR_LOG="$USER_HOME/kiosk-errors.log"
DEPLOYMENT_LOG="$USER_HOME/kiosk-installation.log"

if [ -f "$ERROR_LOG" ]; then
    ERROR_COUNT=$(grep -c "ERROR:" "$ERROR_LOG" 2>/dev/null || echo "0")
    WARNING_COUNT=$(grep -c "WARNING:" "$ERROR_LOG" 2>/dev/null || echo "0")
    
    echo "📊 Error Summary:"
    echo "   • Total Errors: $ERROR_COUNT"
    echo "   • Total Warnings: $WARNING_COUNT"
    echo ""
    
    if [ "$ERROR_COUNT" -eq 0 ] && [ "$WARNING_COUNT" -eq 0 ]; then
        echo "✅ No errors or warnings found!"
        echo ""
    else
        echo "📋 Recent Errors:"
        echo "-----------------------------------------------------------"
        grep -A 5 "ERROR:" "$ERROR_LOG" | tail -20
        echo "-----------------------------------------------------------"
        echo ""
        echo "📋 Recent Warnings:"
        echo "-----------------------------------------------------------"
        grep -A 3 "WARNING:" "$ERROR_LOG" | tail -15
        echo "-----------------------------------------------------------"
        echo ""
    fi
    
    echo "📁 Full error log location:"
    echo "   $ERROR_LOG"
    echo ""
    echo "💡 To view full log:"
    echo "   cat $ERROR_LOG"
    echo ""
    echo "💡 To copy log content:"
    echo "   cat $ERROR_LOG | xclip -selection clipboard"
    echo "   (Or just open the file and copy manually)"
    echo ""
else
    echo "❌ Error log not found at: $ERROR_LOG"
    echo ""
    echo "💡 This may mean:"
    echo "   • Deployment hasn't been run yet"
    echo "   • No errors occurred during deployment"
    echo ""
fi

if [ -f "$DEPLOYMENT_LOG" ]; then
    echo "📁 Deployment log available at:"
    echo "   $DEPLOYMENT_LOG"
    echo ""
fi
