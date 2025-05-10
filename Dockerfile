# Use official PHP image with Apache
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    git \
    && docker-php-ext-install pgsql pdo_pgsql

# Enable Apache mod_rewrite (optional, but common)
RUN a2enmod rewrite

# Set recommended PHP.ini settings (optional)
COPY ./php.ini /usr/local/etc/php/

# Set working directory
WORKDIR /var/www/html

# Copy your app code
COPY . .

# Expose Apache port
EXPOSE 80
