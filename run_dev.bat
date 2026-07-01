@echo off
echo ==========================================
echo SPMI V3 - Local Development Environment
echo ==========================================
echo.
echo Starting Laravel Development Server (php artisan serve)...
start "Laravel Server" cmd /c "php artisan serve"

echo Starting Vite Frontend Build (npm run dev)...
start "Vite Build" cmd /c "npm run dev"

echo Starting Queue Worker (php artisan queue:work)...
start "Queue Worker" cmd /c "php artisan queue:work"

echo.
echo [!] Services are running in separate terminal windows.
echo [!] You can now access the website at: http://127.0.0.1:8000
echo.
pause
