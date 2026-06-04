FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application files to the Apache document root
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Update permissions for the database so SQLite can read/write it
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
