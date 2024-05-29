# Installation

## Using Docker
The following project has Docker setup in it with all the necessary dependencies.
Just run:
```bash
docker compose up
```
And the project will be ready to go in the url http://localhost:8000

Just keep in mind that building the image might take a lot of time as it installs everything from scratch.

In such case you can install the project manually.

## Installing Manually
```bash

```
    - cp .env.example .env
    - composer install
    - php artisan key:generate
    - php artisan migrate (The migration file is only used for running queue jobs as the default queue driver is database)
    - cp -r .php/google-calendar/ storage/app/ (The credential is stored in .php/
