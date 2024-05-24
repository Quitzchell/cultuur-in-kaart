# Stage 1: Base image for installing dependencies
FROM php:8.3-fpm-alpine3.19 AS base

# Set working directory
WORKDIR /var/www/html

# Install dependencies and Composer
RUN apk add --no-cache curl nodejs npm git icu libintl libzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Stage 2: Create image for application
FROM base AS app

# Copy necessary files from the base stage including composer
COPY --from=base /usr/local/bin/composer /usr/local/bin/composer

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli intl zip bcmath

# Copy the rest of the application files
COPY . .

# Install application dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Stage 3: create smaller image size for deployment
FROM php:8.3-fpm-alpine3.19
WORKDIR /var/www/html

# Copy necessary files from the previous stages
COPY --from=app /usr/local/bin/composer /usr/local/bin/composer
COPY --from=app /var/www/html /var/www/html
COPY --from=app /usr/local/lib/php/extensions/no-debug-non-zts-20200930/* /usr/local/lib/php/extensions/no-debug-non-zts-20200930/

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose the port the application runs on
EXPOSE 8080

# Start the PHP-FPM service
CMD ["php-fpm"]
