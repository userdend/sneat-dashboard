# Stage 1: Build frontend assets
FROM node:22 as frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Build base image
FROM php:8.3-apache as base
LABEL maintainer="boyd"
WORKDIR /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# Change Apache document root to Laravel public directory
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf
# Enable Apache modules Laravel needs
RUN a2enmod rewrite headers
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# Copy Laravel application
COPY . .
# Copy compiled Vite assets
COPY --from=frontend /app/public/build ./public/build

# Stage 3: Laravel app - Development
FROM base as development
# Install PHP dependencies
RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist
# Laravel storage permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache
# Optimize Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache
EXPOSE 80
CMD ["apache2-foreground"]

# Stage 3: Laravel app
FROM base as production
# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist
# Laravel storage permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache
# Optimize Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache
EXPOSE 80
CMD ["apache2-foreground"]
