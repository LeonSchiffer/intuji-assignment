#!/bin/sh

composer install

a2enmod rewrite
apachectl -D BACKGROUND
# npm run dev -- --host 0.0.0.0
exec docker-php-entrypoint "$@"
