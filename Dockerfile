# 1. Use official PHP 8.3 FPM image
FROM php:8.3-fpm

# 2. Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Install Composer (official installer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy Laravel project files
COPY . .

# 6. Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Laravel optimizations
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# 8. Set permissions (very important for storage & bootstrap cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
