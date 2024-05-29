FROM php:8.2-apache as php

RUN apt-get update && apt-get install -y nodejs npm
RUN apt-get update && apt-get install supervisor libzip-dev -y
RUN docker-php-ext-install pdo_mysql sockets exif mysqli zip
RUN docker-php-ext-enable mysqli zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .
COPY ./.docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY ./.php/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./.php/google-calendar/service-account-credentials.json /var/www/html/storage/app/google-calendar/service-account-credentials.json

# RUN npm run dev

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
