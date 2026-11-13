FROM php:8.5-fpm

# Xdebug is for local development only; docker-compose turns it on
ARG INSTALL_XDEBUG=false

RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests  -y \
        libpng-dev \
        libxml2-dev \
        libldap2-dev \
        libldb-dev \
        libzip-dev \
        unzip \
        git \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/* \
    && ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so \
    && docker-php-ext-install -j$(nproc) xml gd ldap mysqli pdo_mysql zip \
    && pecl install timezonedb \
    && docker-php-ext-enable timezonedb \
    && if [ "$INSTALL_XDEBUG" = "true" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY docker/php.ini /usr/local/etc/php/
COPY docker/zz-docker-ipeer.conf /usr/local/etc/php-fpm.d/
COPY docker/docker-entrypoint-php-fpm.sh /

# Install packages before the source is copied, so a code change does not
# install them again. The scripts need the source and run after it is copied.
COPY composer.json composer.lock /var/www/html/
RUN cd /var/www/html \
    && composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --optimize-autoloader --no-scripts

COPY . /var/www/html

RUN cd /var/www/html \
    && composer run-script --no-dev post-install-cmd \
    && mkdir -p /var/www/html/app/tmp/cache/persistent /var/www/html/app/tmp/cache/models /var/www/html/app/tmp/logs \
    && chown www-data:www-data -R /var/www/html/app/tmp/cache \
    && chown www-data:www-data -R /var/www/html/app/tmp/logs

RUN set -ex \
    ## Customize PHP fpm configuration
    && sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /usr/local/etc/php-fpm.conf \
    && sed -i -e "s/;request_terminate_timeout\s*=[^\n]*/request_terminate_timeout = 300/g" /usr/local/etc/php-fpm.conf \
    && php-fpm --test

ARG COMMIT_HASH=""
ENV IPEER_COMMIT_HASH=$COMMIT_HASH

CMD ["/docker-entrypoint-php-fpm.sh"]
