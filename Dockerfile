# ---------- Stage 1: Node + Vite build ----------
    FROM node:20-alpine as vite-build

    # Set working directory
    WORKDIR /app
    
    # Copy package files and install dependencies
    COPY package*.json ./
    RUN npm install
    
    # Copy the rest of the code and build assets
    COPY . .
    RUN npm run build
    
    
    # ---------- Stage 2: PHP + Composer build ----------
    FROM php:8.3-fpm-alpine as php-build
    
    # Install required PHP extensions
    RUN apk add --no-cache \
        git \
        unzip \
        oniguruma-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        postgresql-dev \
        && docker-php-ext-install pdo pdo_pgsql intl zip exif gd
    
    # Install Composer
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
    
    # Set working directory
    WORKDIR /var/www/html
    
    # Copy Laravel files
    COPY . .
    
    # Copy built frontend assets from the first stage
    COPY --from=vite-build /app/public/build ./public/build
    
    # Install PHP dependencies
    RUN composer install --no-dev --optimize-autoloader
    
    # Set permissions for storage & bootstrap/cache
    RUN chown -R www-data:www-data storage bootstrap/cache \
        && chmod -R 775 storage bootstrap/cache
    
    # Expose port 9000 for PHP-FPM
    EXPOSE 9000    

# Start Laravel server on 0.0.0.0:9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
