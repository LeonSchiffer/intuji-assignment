# Overview
The following project is an assessment task for **_Laravel Developer_** vacancy in **_Intuji_**. 

It was completed as per the requirement in the docs and was submitted before **_29 May 2024_**.

# Project Overview
Let me explain the different parts of this project.

## 1. Docker
The following project contains a **_Dockerfile_** and **_docker-compose.yml_** to setup **_containers_** to run the project seamlessly. 

It sets up **_PHP8.2_**, **_Apache_**, **_Node.js_**, **_Supervisor_** (for running queue jobs) and **_Redis_** (for caching and running queue jobs)

Please note that when using docker, database isn't setup as it wasn't required to complete the task.

However, when installing the project manually, you have to setup a database as the default queue connection is database. Use SQLite for easy database setup.

## 2. Redis
Redis is being used for two things

1. To **_cache event list_** as it is not neccessary to call the api every time (The GET api takes around _**2 seconds**_)
2. To _**run queue jobs**_. Since we are already using redis, it was not necessary to setup a database for running queue jobs. Redis is also a better option that database for running queues.

## 3. Supervisor
Supervisor has been setup to start the _**queue process**_ in the backgound. This makes sure that the process is restarted if it exits somehow.

You can find the supervisor configuration in **_.docker/supervisor/laravel-worker.conf_** file.

## 4. Laravel with Inertia (Vue)
The following project is using Laravel with PHP 8.2 for backend and Inertia with Vue for frontend.


# Installation

## 1. Using Docker
The following project has Docker setup in it with all the necessary dependencies.
Just run:
```bash
docker compose up
```
And the project will be ready to go in the url http://localhost:8000

Just keep in mind that building the image might take a lot of time as it installs everything from scratch.

If it takes too much time, you can setup the project manually.

## 2. Installing Manually
```bash
cp .env.example .env
```
Copy the environment file. I have also setup **_GOOGLE_CALENDAR_ID_** in the .env.example file. You can use that for testing.

Or if you want, you can setup your own **_GOOGLE_CALENDAR_ID_**. 

Please keep in mind that I'm using **_Service Accounts_** and not **_OAuth Client Id_** for authorization.

You can find my credentials in **_.php/google-calendar/service-account-credentials.json_** file. If you plan to use your own **_GOOGLE_CALENDAR_ID_**, you also have to update this **_service-account-credentials.json_** credentials here with your own.
```bash
cp -r .php/google-calendar/ storage/app/
```
After setting up the credentials, you need to set it up in storage/app/google-calendar folder using the above commmand
```bash
composer install
```
Install all the necessary laravel dependencies
```bash
php artisan key:generate
```
Generate a unique APP_KEY in .env file
```bash
php artisan migrate
```
Database is only used for running queue jobs as the default queue driver is database
