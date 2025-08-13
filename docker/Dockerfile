FROM php:8.3-apache

# Enable apache modules
RUN a2enmod rewrite headers expires

# Set working dir
WORKDIR /var/www/html

# Copy custom vhost
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy source code
COPY . /var/www/html

# Set permissions for apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]