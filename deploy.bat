@echo off
rem -------------------------------------------------------------------------
rem Correr Sistema Modo Desarrollo
rem -------------------------------------------------------------------------

call npm install 
call composer install
call npm run dev
call php artisan migrate
call php artisan route:clear 
call php artisan cache:clear
call php artisan optimize
call php artisan config:clear

IF [%1]==[] (
	php artisan serve --host 0.0.0.0
) ELSE (
    php artisan serve --port=%1 --host 0.0.0.0
)