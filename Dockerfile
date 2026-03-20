FROM php:8.2-fpm-alpine

# 1. Install PHP Extension Installer (FIXED URL)
ADD https://github.com /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# 2. Install extensions
RUN install-php-extensions pdo_mysql bcmath zip intl gd opcache

# 3. Install only essential runtime tools
RUN apk add --no-cache bash git curl

WORKDIR /var/www

# 4. Use the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 5. CACHE LAYER: Composer dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 6. Copy the rest of your app
COPY . .
RUN composer dump-autoload --optimize

# 7. Set permissions
RUN chown -R 1000:1000 /var/www && chmod -R 775 storage bootstrap/cache

USER 1000
EXPOSE 9000
CMD ["php-fpm"]
