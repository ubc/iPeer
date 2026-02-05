FROM php:8.3-apache

EXPOSE 80
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
RUN apt-get update && apt-get install -y zip unzip
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY . .
