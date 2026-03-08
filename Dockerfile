# Dockerfile
FROM php:8.2-fpm

# Installer les dépendances système et PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le code
COPY . .

# Installer les dépendances Laravel via Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
