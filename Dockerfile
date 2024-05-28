FROM php:8.2-apache as php

RUN docker-php-ext-install pdo_mysql sockets exif mysqli
RUN docker-php-ext-enable mysqli


# RUN apt-get update && apt-get install supervisor -y

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .
# COPY ./.docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY ./.php/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN service apache2 restart

# ENTRYPOINT ["/var/www/html/entrypoint.sh"]
