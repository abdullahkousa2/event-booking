FROM php:8.3-cli-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        bash curl libpng-dev libxml2-dev oniguruma-dev zip unzip sqlite-dev \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_sqlite mbstring xml bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first (layer cache)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-cache

# Copy application source
COPY . .

# Set storage permissions (no chown — HF Spaces runs its own user)
RUN mkdir -p storage/framework/{sessions,views,cache/data} \
             storage/logs \
             bootstrap/cache \
             database \
    && chmod -R 777 storage bootstrap/cache \
    && touch database/database.sqlite \
    && chmod 666 database/database.sqlite

# Environment for HF Spaces (SQLite, no MySQL needed)
ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/html/database/database.sqlite \
    SESSION_DRIVER=file \
    CACHE_STORE=file \
    QUEUE_CONNECTION=sync \
    LOG_CHANNEL=stderr

EXPOSE 8000

# Generate key → migrate → seed → serve
CMD sh -c "php artisan key:generate --force && \
           php artisan migrate --force && \
           php artisan db:seed --force && \
           php artisan serve --host=0.0.0.0 --port=8000"
