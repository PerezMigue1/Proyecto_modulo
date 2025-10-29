# Dockerfile para Laravel en Render

FROM php:8.2-cli

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    openssl \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar extensión MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos
COPY . /var/www/html

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader
RUN npm ci
RUN npm run build

# Hacer ejecutable el script de inicio (ya está en .)
RUN chmod +x /var/www/html/start.sh

# Exponer puerto
EXPOSE 8000

# Usar el script de inicio
CMD ["/var/www/html/start.sh"]
