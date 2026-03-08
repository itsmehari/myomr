# MyOMR Database SSH Tunnel - PowerShell Script
# This script creates an SSH tunnel to connect local Windows to remote MySQL database

Write-Host "========================================" -ForegroundColor Green
Write-Host "MyOMR Database SSH Tunnel Setup" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Configuration
$REMOTE_HOST = "myomr.in"
$REMOTE_PORT = 22
$LOCAL_PORT = 3307
$REMOTE_MYSQL_PORT = 3306

Write-Host "📋 Configuration:" -ForegroundColor Cyan
Write-Host "   Remote Host: $REMOTE_HOST"
Write-Host "   SSH Port: $REMOTE_PORT"
Write-Host "   Local Port: $LOCAL_PORT"
Write-Host "   Remote MySQL Port: $REMOTE_MYSQL_PORT"
Write-Host ""

# Check if SSH is available
Write-Host "🔍 Checking SSH availability..." -ForegroundColor Yellow
$sshAvailable = Get-Command ssh -ErrorAction SilentlyContinue

if (-not $sshAvailable) {
    Write-Host "❌ ERROR: SSH is not available on your system" -ForegroundColor Red
    Write-Host ""
    Write-Host "To install SSH on Windows:" -ForegroundColor Yellow
    Write-Host "1. Open Settings → Apps → Optional Features" -ForegroundColor White
    Write-Host "2. Click 'Add a feature'" -ForegroundColor White
    Write-Host "3. Search for 'OpenSSH Client'" -ForegroundColor White
    Write-Host "4. Install it" -ForegroundColor White
    Write-Host ""
    Write-Host "Or use this PowerShell command (as Administrator):" -ForegroundColor Yellow
    Write-Host "Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0" -ForegroundColor Cyan
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "✅ SSH is available" -ForegroundColor Green
Write-Host ""

# Check if port is already in use
Write-Host "🔍 Checking if port $LOCAL_PORT is available..." -ForegroundColor Yellow
$portInUse = Get-NetTCPConnection -LocalPort $LOCAL_PORT -ErrorAction SilentlyContinue

if ($portInUse) {
    Write-Host "⚠️  WARNING: Port $LOCAL_PORT is already in use" -ForegroundColor Yellow
    Write-Host ""
    $continue = Read-Host "Do you want to continue anyway? (y/n)"
    if ($continue -ne "y") {
        Write-Host "Exiting..." -ForegroundColor Red
        exit 1
    }
}
else {
    Write-Host "✅ Port $LOCAL_PORT is available" -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "SSH Tunnel Instructions" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Prompt for username
Write-Host "Please enter your cPanel SSH username:" -ForegroundColor Cyan
Write-Host "(This is usually your cPanel login username)" -ForegroundColor Gray
$username = Read-Host "Username"

if ([string]::IsNullOrWhiteSpace($username)) {
    Write-Host "❌ ERROR: Username cannot be empty" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Starting SSH Tunnel..." -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Construct SSH command
$sshCommand = "ssh -L ${LOCAL_PORT}:localhost:${REMOTE_MYSQL_PORT} ${username}@${REMOTE_HOST}"

Write-Host "🚀 Executing SSH tunnel command:" -ForegroundColor Yellow
Write-Host $sshCommand -ForegroundColor Cyan
Write-Host ""
Write-Host "📝 You will be prompted for your cPanel password" -ForegroundColor Yellow
Write-Host ""
Write-Host "⚠️  IMPORTANT:" -ForegroundColor Red
Write-Host "   • Keep this window OPEN while working" -ForegroundColor White
Write-Host "   • The tunnel only works while this connection is active" -ForegroundColor White
Write-Host "   • Press Ctrl+C to stop the tunnel when done" -ForegroundColor White
Write-Host ""
Write-Host "✅ After connection is established:" -ForegroundColor Green
Write-Host "   • Your PHP files can connect to localhost:$LOCAL_PORT" -ForegroundColor White
Write-Host "   • Traffic will be forwarded to remote MySQL securely" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Test your connection at:" -ForegroundColor Cyan
Write-Host "   http://localhost/test-remote-db-connection.php" -ForegroundColor White
Write-Host ""

# Wait a moment
Start-Sleep -Seconds 2

Write-Host "Connecting..." -ForegroundColor Yellow
Write-Host ""

# Execute SSH command
Invoke-Expression $sshCommand

# This line only executes if SSH exits
Write-Host ""
Write-Host "========================================" -ForegroundColor Yellow
Write-Host "SSH Tunnel Closed" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "The SSH tunnel has been closed." -ForegroundColor Yellow
Write-Host "Your PHP application can no longer connect to the remote database." -ForegroundColor Yellow
Write-Host ""
Write-Host "To reconnect, run this script again." -ForegroundColor Cyan
Write-Host ""

Read-Host "Press Enter to exit"
