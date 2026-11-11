#!/usr/bin/env bash
set -euo pipefail

echo "=========================================================="
echo "  Tatanikah — Automated Production Deploy"
echo "=========================================================="

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$APP_DIR"

# 1. Environment file validation
if [ ! -f .env ]; then
    echo "[!] File .env tidak ditemukan. Menyalin dari .env.example..."
    cp .env.example .env
fi

# 2. Build & Launch Docker Containers
echo "[+] Menjalankan build container Docker (PHP 8.4-FPM + Nginx + Postgres + Redis)..."
docker compose build --pull
docker compose up -d

echo "[+] Menunggu database PostgreSQL siap menerima koneksi..."
docker compose exec -T postgres sh -c 'until pg_isready -U tatanikah -d wedding_planner; do sleep 1; done'

# 3. Application Key Generation (if empty)
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    echo "[+] Menghasilkan application key..."
    docker compose exec -T app php artisan key:generate --force
fi

# 4. Database Migrations
echo "[+] Menjalankan migrasi database PostgreSQL..."
docker compose exec -T app php artisan migrate --force

# Optional Seeding
if [[ "${1:-}" == "--seed" ]]; then
    echo "[+] Melakukan seeding data awal (Master Seeder)..."
    docker compose exec -T app php artisan db:seed --force
fi

# 5. Production Optimization Cache
echo "[+] Mengoptimalkan cache Laravel (config, routes, views, events)..."
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan event:cache

echo "=========================================================="
echo "  [SUCCESS] Deployment Selesai!"
echo "  Aplikasi aktif pada: http://localhost:8080"
echo "  Konsol Admin:        http://localhost:8080/admin"
echo "=========================================================="
