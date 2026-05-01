# ---------- Stage 1: Node + Vite ----------
FROM node:20-alpine AS vite-build

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ---------- Stage 2: PHP ----------
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git unzip oniguruma-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip exif gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ❗ Copy app FIRST
COPY . .

# ❗ THEN overwrite with built assets
COPY --from=vite-build /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]