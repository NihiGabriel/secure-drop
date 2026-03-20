FROM php:8.2-fpm-alpine

# 1. Install PHP Extension Installer (ENSURE THIS URL IS ONE LONG LINE)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# 2. Install extensions
RUN install-php-extensions pdo_mysql bcmath zip intl gd opcache

# 3. Install runtime tools
RUN apk add --no-cache bash git curl

WORKDIR /var/www

# 4. Use official Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 5. Cache Layer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 6. Copy App
COPY . .
RUN composer dump-autoload --optimize

# 7. Permissions
RUN chown -R 1000:1000 /var/www && chmod -R 775 storage bootstrap/cache

USER 1000
EXPOSE 9000
CMD ["php-fpm"]
