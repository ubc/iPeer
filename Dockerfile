FROM php:8.3-fpm

RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests  -y \
        libpng-dev \
        libxml2-dev \
        libldap2-dev \
        libldb-dev \
        libzip-dev \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/* \
    && ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so \
    && docker-php-ext-install -j$(nproc) xml gd ldap mysqli pdo_mysql zip \
    && pecl install timezonedb xdebug\
    && docker-php-ext-enable timezonedb xdebug

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY docker/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY docker/docker-entrypoint-php-fpm.sh /

RUN cd /var/www/html \
    && composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --optimize-autoloader \
    && mkdir -p /var/www/html/app/tmp/cache/persistent /var/www/html/app/tmp/cache/models /var/www/html/app/tmp/logs \
    && chown www-data:www-data -R /var/www/html/app/tmp/cache \
    && chown www-data:www-data -R /var/www/html/app/tmp/logs

RUN set -ex \
    ## Customize PHP fpm configuration
    && sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /usr/local/etc/php-fpm.conf \
    && sed -i -e "s/;request_terminate_timeout\s*=[^\n]*/request_terminate_timeout = 300/g" /usr/local/etc/php-fpm.conf \
    && php-fpm --test

##COPY docker/guard-override.php /var/www/html/app/config/guard.php

##COPY docker/guard-controller-override.php /var/www/html/app/plugins/guard/controllers/guard_controller.php

##COPY docker/guard-controllers-components-guard-override.php /var/www/html/app/plugins/guard/controllers/components/guard.php

## Modifying the login page
COPY docker/login_default-override.ctp /var/www/html/app/plugins/guard/views/elements/login_default.ctp
COPY docker/guard_default-override.php /var/www/html/app/plugins/guard/config/guard_default.php

## IMPORTANT MODULES FOR TESTING LOGIN with admin2
COPY docker/auth_module-override.php /var/www/html/app/plugins/guard/controllers/components/auth_module.php
COPY docker/default_module-override.php /var/www/html/app/plugins/guard/controllers/components/default_module.php


CMD ["/docker-entrypoint-php-fpm.sh"]
