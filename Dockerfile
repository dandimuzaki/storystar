# ---------- Stage 1: Node + Vite build ----------
    FROM node:20-alpine as vite-build

    WORKDIR /app
    
    # Copy package files and install dependencies
    COPY package*.json ./
    RUN npm install
    
    # Copy the rest of the source and build assets
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
        && docker-php-ext-install pdo pdo_pgsql intl zip ex_
    