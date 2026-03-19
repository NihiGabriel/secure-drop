FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache bash git curl nginx libpng libpng-dev libjpeg-turbo libjpeg-turbo-dev oniguruma-dev zlib-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Expose port for Traefik
EXPOSE 80

# Start PHP-FPM and Nginx
CMD ["php-fpm"]
