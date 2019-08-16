:loop
php artisan schedule:run 1>> NUL 2>&1
sleep 60
goto loop
