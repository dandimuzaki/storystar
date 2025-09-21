# ---------- Stage 1: Node + Vite build ----------
    FROM node:20-alpine as vite-build

    WORKDIR /app
    
    COPY package*.json ./
    RUN npm install
    
    COPY . .
    RUN npm run build   # ✅ build production assets
    
    
    # ---------- Stage 2: PHP + Composer build ----------
    FROM php:8.3-fpm-alpine as php-build
    
    RUN apk add --no-cache \
        git unzip oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        libzip-dev icu-dev postgresql-dev \
        && docker-php-ext-install pdo pdo_pgsql intl zip exif gd
    
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    WORKDIR /var/www/html
    
    COPY . .
    
    # ✅ Copy only the built assets to public/build
    COPY --from=vite-build /app/public/build ./public/build
    
    RUN composer install --no-dev --optimize-autoloader
    
    RUN chown -R www-data:www-data storage bootstrap/cache \
        && chmod -R 775 storage bootstrap/cache
    
    EXPOSE 9000
    CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
    