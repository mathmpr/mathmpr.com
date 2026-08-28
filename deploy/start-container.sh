#!/bin/sh
set -e

mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php-fpm -D
exec nginx -g "daemon off;"

