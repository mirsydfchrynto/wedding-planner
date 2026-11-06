# Stage 1: Build Frontend Assets via Node.js
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline --no-audit
COPY vite.config.js resources ./
COPY resources ./resources
RUN npm run build

# Stage 2: Production PHP 8.4-FPM
FROM php:8.4-fpm-alpine AS production

# Install System Dependencies & PHP Extension Libraries
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    sqlite-dev \
    oniguruma-dev \
    linux-headers

# Configure & Install PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# Copy Composer from Official Image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set Working Directory
WORKDIR /var/www

# Copy Application Dependency Files
COPY composer.json composer.lock ./

# Install Composer Dependencies (optimized for production)
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Copy Source Code
COPY . .

# Copy Built Assets from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Setup Permissions for Web Server & Storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Production OPcache Configuration
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

EXPOSE 9000

CMD ["php-fpm"]
