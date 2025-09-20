# 1. Use PHP 8.3 FPM as base image (fixes your PHP version issue)
FROM php:8.3-fpm

# 2. Install system packages and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install gd exif pdo pdo_mysql zip opcache \
    && docker-php-ext-enable exif

# 3. Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Set working directory inside container
WORKDIR /var/www/html

# 5. Copy everything from host machine to container
COPY . .

# 6. Install Laravel dependencies
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# 7. Run Laravel optimizations (optional but good for production)
# RUN php artisan config:cache \
#    && php artisan route:cache \
#    && php artisan view:cache

# 8. Start PHP-FPM
CMD ["php-fpm"]
