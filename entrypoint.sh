#!/bin/sh

composer install

# if [ -f ".env" ]; then
#     echo "env file already exists"
# else
#     echo "copying .env.example to .env"
#     cp .env.example .env
# fi
# php artisan migrate
# php artisan optimize:clear
# supervisord
# nohup php artisan queue:work --daemon &

php artisan serve --host=0.0.0.0
exec docker-php-entrypoint "$@"
