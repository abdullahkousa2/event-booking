FROM php:8.3-cli-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        bash \
        curl \
        libpng-dev \
        libxml2-dev \
        oniguruma-dev \
        zip \
        unzip \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        xml \
        bcmath \
        gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first (layer cache)
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev, optimized autoloader)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-cache

# Copy application source
COPY . .

# Set storage and cache permissions
RUN mkdir -p storage/framework/{sessions,views,cache/data} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Run as non-root
USER www-data

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
