FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html/

# Copy project files to the container
COPY . /var/www/html/

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
