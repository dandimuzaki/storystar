# 1. Use PHP image with extensions
FROM php:8.2-fpm

# 2. Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    git \
    curl

# 3. Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy project files
COPY . .

# 6. Create SQLite database file (important!)
RUN mkdir -p database && touch database/database.sqlite

# 7. Install composer dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 8. Cache Laravel config, routes, and views
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# 9. Expose port 8000
EXPOSE 8000

# 10. Start Laravel app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
