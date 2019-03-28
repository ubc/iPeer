FROM php:7.2-fpm

RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests  -y \
        libpng-dev \
        libxml2-dev \
        libldap2-dev \
        libldb-dev \
        unzip \
        libpcre3-dev \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so \
    && docker-php-ext-install -j$(nproc) xml gd ldap mysqli pdo_mysql\
    && pecl install timezonedb \
    && docker-php-ext-enable timezonedb \
    && curl https://getcomposer.org/download/1.8.4/composer.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && pecl install -f oauth-2.0.2 \
    && pecl install -f zip

COPY docker/php.ini /usr/local/etc/php/
COPY . /var/www/html

RUN cd /var/www/html \
    && composer install \
    && mkdir -p /var/www/html/app/tmp/cache/persistent /var/www/html/app/tmp/cache/models \
    && chown www-data:www-data -R /var/www/html/app/tmp/cache

RUN echo "extension=oauth.so" >> /usr/local/etc/php/php.ini
RUN echo "extension=zip.so" >> /usr/local/etc/php/php.ini
RUN echo "opcache.enable=0" >> /usr/local/etc/php/php.ini