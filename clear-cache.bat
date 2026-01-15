@echo off
echo ========================================
echo   LIMPIANDO CACHE DE LARAVEL
echo ========================================
echo.

echo [1/7] Limpiando configuracion...
php artisan config:clear

echo [2/7] Limpiando cache de aplicacion...
php artisan cache:clear

echo [3/7] Limpiando rutas...
php artisan route:clear

echo [4/7] Limpiando vistas...
php artisan view:clear

echo [5/7] Limpiando eventos...
php artisan event:clear

echo [6/7] Optimizando autoload...
composer dump-autoload

echo [7/7] Limpiando cache de opcache (si existe)...
php artisan optimize:clear

echo.
echo ========================================
echo   CACHE LIMPIADO EXITOSAMENTE
echo ========================================
echo.
echo Presiona cualquier tecla para cerrar...
pause >nul