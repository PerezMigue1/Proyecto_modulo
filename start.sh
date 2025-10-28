#!/bin/bash
set -e

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || touch .env
fi

# Generar APP_KEY si no existe
if ! grep -q "APP_KEY=" .env; then
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
php -S 0.0.0.0:$PORT -t public

