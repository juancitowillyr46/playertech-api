#!/bin/sh
set -e

mkdir -p /var/www/html/var/cache /var/www/html/var/log
mkdir -p /var/www/html/public/media
chown -R www-data:www-data /var/www/html/var
chown -R www-data:www-data /var/www/html/public/media
chmod -R ug+rwX /var/www/html/var
chmod -R ug+rwX /var/www/html/public/media

exec "$@"
