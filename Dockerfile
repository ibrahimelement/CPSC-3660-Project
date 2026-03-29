FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Enable Apache mod_rewrite (useful for later routing)
RUN a2enmod rewrite

# Tell Apache to trust .htaccess overrides in the document root
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy project source into the web root
COPY . /var/www/html/

# Fix ownership so Apache can read everything
RUN chown -R www-data:www-data /var/www/html
