# Dockerfile
FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install necessary PHP extensions and required tools
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_mysql


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy only composer files first (for better Docker layer caching)
COPY composer.json composer.lock ./

# Run composer install to download and install dependencies into the 'vendor' folder
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Install dependencies (including OneLogin SAML2 and xmlseclibs)
RUN composer require onelogin/php-saml robrichards/xmlseclibs --prefer-dist --optimize-autoloader


# Set working directory
WORKDIR /var/www/html

# Copy the project files into the container
COPY . /var/www/html/


# Copy the entrypoint script into the container
COPY docker-entrypoint.sh /usr/local/bin/

# Make sure the entrypoint script is executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh



# Expose port 80
EXPOSE 80


# Use the custom entrypoint script
ENTRYPOINT ["/bin/sh", "/usr/local/bin/docker-entrypoint.sh"]

# Set the entry point (if you need custom entry point)
CMD ["apache2-foreground"]