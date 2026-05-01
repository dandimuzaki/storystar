# ---------- Stage 1: Node + Vite ----------
FROM node:20-alpine AS vite-build

WORKDIR /app

# copy only package first (better caching)
COPY package*.json ./
RUN npm install

# copy rest of project
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

COPY . .

# copy built assets (IMPORTANT)
COPY --from=vite-build /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]