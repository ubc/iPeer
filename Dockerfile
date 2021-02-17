FROM php:7.2-fpm

RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests  -y \
        libpng-dev \
        libxml2-dev \
        libldap2-dev \
        libldb-dev \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/* \
    && ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so \
    && docker-php-ext-install -j$(nproc) xml gd ldap mysqli pdo_mysql \
    && pecl install timezonedb xdebug\
    && docker-php-ext-enable timezonedb xdebug\
    && curl https://getcomposer.org/download/1.8.4/composer.phar -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

COPY docker/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY docker/docker-entrypoint-php-fpm.sh /

RUN cd /var/www/html \
    && composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-suggest --optimize-autoloader \
    && mkdir -p /var/www/html/app/tmp/cache/persistent /var/www/html/app/tmp/cache/models /var/www/html/app/tmp/logs \
    && chown www-data:www-data -R /var/www/html/app/tmp/cache \
    && chown www-data:www-data -R /var/www/html/app/tmp/logs

RUN set -ex \
    ## Customize PHP fpm configuration
    && sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /usr/local/etc/php-fpm.conf \
    && sed -i -e "s/;request_terminate_timeout\s*=[^\n]*/request_terminate_timeout = 300/g" /usr/local/etc/php-fpm.conf \
    && php-fpm --test

CMD ["/docker-entrypoint-php-fpm.sh"]