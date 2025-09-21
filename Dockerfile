# Stage 1 - Build assets
FROM node:20 AS frontend
WORKDIR /app

# Copy only package.json first to cache deps
COPY package.json package-lock.json ./
RUN npm ci

# Copy rest of frontend code and build
COPY . .
RUN npm run build

# Stage 0: Build PHP + dependencies
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip gd bcmath mbstring exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy everything including built assets from previous stage
COPY --from=frontend /app /resources

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start Laravel server on 0.0.0.0:9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
