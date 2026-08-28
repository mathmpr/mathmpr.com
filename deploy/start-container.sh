#!/bin/sh
set -e

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
if [ ! -e public/storage ]; then
    ln -s ../storage/app/public public/storage
fi
chown -R www-data:www-data storage bootstrap/cache

php-fpm -D
exec nginx -g "daemon off;"
