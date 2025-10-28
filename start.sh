#!/bin/bash
set -e

# Si no existe APP_KEY en .env, generarlo
if ! grep -q "APP_KEY=" .env 2>/dev/null; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
fi

# Cachear configuraciones
echo "Cacheando configuraciones..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar servidor
echo "Iniciando servidor..."
php -S 0.0.0.0:8000 -t public
