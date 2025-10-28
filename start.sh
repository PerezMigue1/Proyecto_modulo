#!/bin/bash
set -e

echo "=== Iniciando aplicacion Laravel ==="

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || touch .env
fi

# Siempre generar APP_KEY
echo "Generando APP_KEY..."
php artisan key:generate --force

# Cachear configuraciones
echo "Cacheando configuraciones..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mostrar APP_KEY para debugging
echo "=== APP_KEY generado ==="
grep APP_KEY .env || echo "No APP_KEY found"

# Iniciar servidor
echo "=== Iniciando servidor en puerto $PORT ==="
php -S 0.0.0.0:$PORT -t public

