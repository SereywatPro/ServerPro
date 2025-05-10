FROM php:8.1-apache

# Install PostgreSQL extension
RUN docker-php-ext-install pgsql pdo_pgsql

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Copy all backend files to Apache server root
COPY . /var/www/html/

# Set permissions (optional)
RUN chown -R www-data:www-data /var/www/html/uploads
