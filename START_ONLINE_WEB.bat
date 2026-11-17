@echo off
title Azzahra Salon - Online Web Server
color 0A
echo =========================================================
echo    MENJALANKAN AZZAHRA SALON ^& SPA ONLINE KE INTERNET
echo =========================================================
echo.
echo 1. Menjalankan PHP Artisan Server di port 8000...
start /min "Laravel Backend" php artisan serve --port=8000
timeout /t 3 /nobreak >nul
echo.
echo 2. Membuka Terowongan Cloudflare (Public HTTPS Tunnel)...
echo ---------------------------------------------------------
echo Tunggu beberapa detik, cari baris yang memuat link:
echo "https://....trycloudflare.com"
echo.
echo Salin link tersebut dan berikan ke Dosen / Pengguna!
echo (Biarkan jendela ini tetap terbuka selama web ingin diakses)
echo ---------------------------------------------------------
echo.
"C:\Program Files (x86)\cloudflared\cloudflared.exe" tunnel --url http://localhost:8000
pause
