# ---------- Stage 1: Node + Vite build ----------
    FROM node:20-alpine AS vite-build

    WORKDIR /app
    
    # Install Node dependencies
    COPY package*.json ./
    RUN npm ci
    
    # Copy project and build Vite assets
    COPY . .
    RUN npm run build
    
    
    # ---------- Stage 2: PHP + Composer + Laravel ----------
    FROM php:8.3-fpm-alpine
    
    # Install PHP extensions
    RUN apk add --no-cache \
        git unzip oniguruma-dev \
        libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev postgresql-dev \
        && docker-php-ext-install pdo pdo_pgsql intl zip exif gd
    
    # Install Composer (copy from official Composer image)
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    WORKDIR /var/www/html
    
    # Copy application files
    COPY . .
    
    # ✅ Copy built Vite assets from previous stage
    COPY --from=vite-build /app/public/build ./public/build
    
    # Install PHP dependencies for production
    RUN composer install --no-dev --optimize-autoloader --no-interaction
    
    # Set correct permissions for Laravel
    RUN chown -R www-data:www-data storage bootstrap/cache \
        && chmod -R 775 storage bootstrap/cache
    
    EXPOSE 9000
    
    # ✅ Run with artisan serve on port 8080 (matches Railway default)
    CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
    