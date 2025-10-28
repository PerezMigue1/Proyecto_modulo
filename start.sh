#!/bin/bash
# NO usar set -e para continuar aunque falle algo

echo "=== Iniciando aplicacion Laravel ==="

# Crear .env si no existe
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || touch .env
fi

# Siempre generar APP_KEY
echo "Generando APP_KEY..."
if php artisan key:generate --force; then
    echo "APP_KEY generado exitosamente"
else
    echo "APP_KEY generation failed, trying to continue anyway..."
fi

# NO cachear configuraciones por ahora - permite que lea .env
echo "Skipping config cache to allow .env reading..."

# Mostrar APP_KEY para debugging
echo "=== APP_KEY generado ==="
grep APP_KEY .env || echo "No APP_KEY found"

# Iniciar servidor
echo "=== Iniciando servidor en puerto $PORT ==="
php -S 0.0.0.0:$PORT -t public

