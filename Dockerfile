# ---------- Stage 1: Node + Vite ----------
FROM node:20-alpine AS vite-build

WORKDIR /app

COPY . .
RUN npm install
RUN npm run build

# ---------- Stage 2: PHP ----------
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git unzip oniguruma-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip exif gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# copy built assets
COPY --from=vite-build /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]