# Stage 1: Base image for installing dependencies
FROM php:8.3-fpm-alpine3.19 AS base

# Set working directory
WORKDIR /var/www/html

# Install dependencies and Composer
RUN apk add --no-cache curl nodejs npm git icu libintl libzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Stage 2: Create image for application
FROM base AS build

# Install additional PHP extensions
RUN apk add --no-cache \
    php82-intl \
    php82-zip

# Install dependencies
RUN apk add --no-cache \
    php82-pdo \
    php82-pdo_mysql \
    php82-mysqli \
    php82-bcmath

# Create a non-root user
RUN adduser -D user

# Set ownership of application files to the non-root user
RUN chown -R user:user /var/www/html

# Switch to the non-root user
USER user

# Copy the rest of the application files
COPY . .

# Install application dependencies using Composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip

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
COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html

# Expose the port the application runs on
EXPOSE 8080

# Start the PHP-FPM service
CMD ["php-fpm"]
