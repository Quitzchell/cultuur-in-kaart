# Stage 1: Base image for installing dependencies
FROM php:8.3-fpm-alpine3.19 AS base

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache \
    curl \
    nodejs \
    npm \
    git \
    icu-dev \
    libintl \
    libzip-dev \
    supervisor

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Stage 2: Create image for application
FROM base AS build

# Install additional PHP extensions
RUN docker-php-ext-install intl zip

# Copy the rest of the application files
COPY . .

# Install application dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Stage 3: create smaller image size for deployment
FROM php:8.3-fpm-alpine3.19

# Install necessary PHP extensions and other dependencies
RUN apk add --no-cache \
    php82-pdo_mysql \
    php82-mysqli \
    php82-intl \
    php82-zip \
    php82-bcmath \
    nginx \
    supervisor

# Set working directory
WORKDIR /var/www/html

# Copy necessary files from the build stage
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

# Copy Nginx and Supervisor configuration files
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

# Expose the port the application runs on
EXPOSE 80

# Start Supervisor
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
