# ---------- Stage 1: Node + Vite build ----------
FROM node:20-alpine AS vite-build

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ---------- Stage 2: PHP + Laravel ----------
FROM php:8.4-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd

COPY . .
COPY --from=vite-build /app/public/build ./public/build

# ✅ CREATE SQLITE FILE HERE (correct place)
RUN mkdir -p database \
    && touch database/database.sqlite

# Install dependencies
RUN curl -sS https://getcomposer.org/installer | php \
    && php composer.phar install --no-dev --optimize-autoloader

# Apache config
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80