#!/bin/sh
set -e

# Buat database sqlite jika belum ada
touch /var/www/database/database.sqlite
chmod 777 /var/www/database/database.sqlite

# Migrasi database
php artisan migrate --force

# Seed jika database masih kosong
php artisan db:seed --force

# Jalankan server aplikasi
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
