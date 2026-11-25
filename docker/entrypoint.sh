#!/bin/sh
set -e

# Espera a que MySQL acepte conexiones (el healthcheck de Compose también lo garantiza).
until php -r '$h = getenv("DB_HOST") ?: "db"; $p = (int) (getenv("DB_PORT") ?: 3306); $s = @fsockopen($h, $p, $errno, $errstr, 2); if (! $s) { exit(1); } fclose($s); exit(0);' >/dev/null 2>&1; do
    echo "Esperando a MySQL en ${DB_HOST:-db}:${DB_PORT:-3306}..."
    sleep 2
done

# El archivo .env no se versiona: se crea desde la plantilla dentro del contenedor.
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Genera APP_KEY si aún no existe (necesario para Laravel).
if ! grep -q '^APP_KEY=base64:' /var/www/html/.env; then
    php artisan key:generate --force --ansi
fi

# Prepara los cachés ligeros de Laravel para esta ejecución.
php artisan package:discover --ansi
php artisan config:clear --ansi
php artisan view:clear --ansi
php artisan route:clear --ansi

echo "Aplicación lista en http://localhost:8000"

exec "$@"
