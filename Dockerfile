# Stage 1: Base image for installing dependencies
FROM php:8.3-fpm-alpine3.19 AS base

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache curl \
    nodejs \
    npm \
    git \
    icu \
    libzip \
    libintl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Stage 2: Create image for application
FROM base AS build

# Copy necessary files from the base stage including composer
COPY --from=base /usr/local/bin/composer /usr/local/bin/composer

# Install dependencies
RUN apk add --no-cache \
    php82-pdo \
    php82-pdo_mysql \
    php82-mysqli \
    php82-intl \
    php82-zip \
    php82-bcmath

# Copy the rest of the application files
COPY . .

# Install application dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Stage 3: create smaller image size for deployment
FROM php:8.3-fpm-alpine3.19
WORKDIR /var/www/html

# Install necessary PHP extensions
RUN apk add --no-cache \
    php82-pdo \
    php82-pdo_mysql \
    php82-mysqli \
    php82-intl \
    php82-zip \
    php82-bcmath

# Copy necessary files from the previous stages
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
COPY --from=build /var/www/html /var/www/html

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose the port the application runs on
EXPOSE 8080

# Start the PHP-FPM service
CMD ["php-fpm"]
