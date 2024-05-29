#!/bin/sh

composer install

if [ ! -f ".env" ]; then
    echo "Creating a new env file for $APP_NAME"
    cp .env.docker .env
    php artisan key:generate
else
    echo "env file already exists"
fi
a2enmod rewrite
apachectl -D BACKGROUND
chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache
mkdir ./storage/app/google-calendar
cp ./.php/google-calendar/service-account-credentials.json ./storage/app/google-calendar/service-account-credentials.json
supervisord
npm install
npm run dev -- --host 0.0.0.0
exec docker-php-entrypoint "$@"
