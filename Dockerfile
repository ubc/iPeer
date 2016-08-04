FROM php:5.6.24-fpm

RUN apt-get update && apt-get install -y \
        libpng12-dev \
        libxml2-dev \
        libldap2-dev \
        libldb-dev \
    && ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so \
    && docker-php-ext-install -j$(nproc) xml gd ldap mysql \
    && curl https://getcomposer.org/download/1.2.0/composer.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

COPY docker/php.ini /usr/local/etc/php/
COPY . /var/www/html

RUN cd /var/www/html \
    && composer install
