# 1. Use official PHP with extensions
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# Optimize Laravel
#RUN php artisan config:clear \
# && php artisan route:clear \
# && php artisan view:clear \
# && php artisan cache:clear \
# && php artisan config:cache \
# && php artisan route:cache \
# && php artisan view:cache

# Expose port
EXPOSE 8080

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8080