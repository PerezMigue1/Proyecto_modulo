#!/bin/bash
set -e

echo "=== Iniciando aplicacion Laravel ==="

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || touch .env
fi

# Generar APP_KEY si no existe
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
else
    echo "APP_KEY ya existe, omitiendo generacion"
fi

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

