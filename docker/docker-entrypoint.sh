#!/bin/bash

set -eo pipefail

config=/etc/nginx/conf.d/default.conf
NGINX_FASTCGI_PASS=${NGINX_FASTCGI_PASS:-localhost:9000}

if [ "${1:0:1}" = '-'  ]; then
    set -- nginx "$@"
fi

sed -Ei "s/NGINX_FASTCGI_PASS/$NGINX_FASTCGI_PASS/" $config

exec "$@"
