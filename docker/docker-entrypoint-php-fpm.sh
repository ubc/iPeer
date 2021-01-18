#!/bin/sh

# hacks to allow php-fpm child process to write to log files that redirected to stderr
# similar to https://github.com/moby/moby/issues/6880#issuecomment-344114520
chown www-data:www-data /var/www/html/app/tmp/logs
for f in /var/www/html/app/tmp/logs/api.log \
    /var/www/html/app/tmp/logs/debug.log \
    /var/www/html/app/tmp/logs/error.log \
    /var/www/html/app/tmp/logs/login.log
do
    rm -f $f
    mkfifo -m 600 $f
    chown www-data:www-data $f
    cat <> $f 1>&2 &
done

php-fpm
