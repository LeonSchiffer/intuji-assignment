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
```bash
npm install
```
For installing the node modules
```bash
npm run dev
```
For starting the node development environment

# Project Structure

## Laravel External Dependencies Used
- [spatie/laravel-google-calendar](https://github.com/spatie/laravel-google-calendar)
- predis/predis
- inertiajs/inertia-laravel
- [nunomaduro/larastan](https://github.com/larastan/larastan): For code analysis
  
## Vue dependencies used
- [vue3-toastify](https://www.npmjs.com/package/vue3-toastify)
- [vuetify](https://vuetifyjs.com/)

## Features
The project does three tasks as mentioned in the docs i.e.
- List events
- Create events
- Delete events

## Files

#### 1. web.php
The web.php only has one route i.e. the **_"/"_** home route. You can _**list**_, _**create**_ and _**delete events**_ from here.
```php
Route::get('/', function () {
    return Inertia::render("Home");
});
```

#### 2. api.php
This has 3 routes. One for _**listing events**_, another for **_creating events_**, and the last one for **_deleting events_**
```php
Route::apiResource("/events", EventController::class)->only(["index", "store", "destroy"]);
```

#### 3. EventController
```php
class EventController extends Controller
{
    public function __construct(private CalendarService $calendar)
    {
    }

    /**
     * Get a list of events from google calendar
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Check the project for code
    }

    /**
     * Create a new event in Google calendar
     */
    public function store(CreateEventRequest $request): \Illuminate\Http\JsonResponse
    {

        // Check the project for code
    }

    /**
     * Remove the specified event from google calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $event_id)
    {
       // Check the project for code
    }
}

```
This controller is response for handling all the API requests.

It has **_index_**, **_store_**, and **_destroy_** methods which does its respective tasks.

#### 4. App\Services\CalendarService
```php
<?php

namespace App\Services;

class CalendarService
{
    /**
     * @param ?string $start_time
     * @param ?string $end_time
     * @param array<string, mixed> $parameters
     * @return \Illuminate\Support\Collection<int, Event>
     */
    public function getEvents()
    {
        // Check the project for code
    }

    /**
     * @param EventDto $eventDto
     * @return mixed
     */
    public function store(EventDto $eventDto)
    {
        // Check the project for code
    }

    /**
     * @param string $event_id
     * @return Event
     */
    public function find(string $event_id): Event
    {
        // Check the project for code
    }

    /**
     * @param string $event_id
     * @return void
     */
    public function delete(string $event_id)
    {
        // Check the project for code
    }

    /**
     * Removes the event only if the event list has been cached
     * @param string $event_id
     * @return void
     */
    public function removeEventFromCache(string $event_id)
    {
        // Check the project for code
    }
}

```
Although the controller handles the requests, the logic for manipulating the events is stored inside the CalendarService class.

This is so that the controller doesn't know how the events are being handled and to introduce separation of concerns.

#### 5. App\DataTransferObjects\EventDto.php
The **_EventDto_** was created for data handling and management across the application. It also allows for type hinting which makes coding easier.

It is used in **_EventController::store()_** method.
```php
class EventDto
{
    /**
     * Will create a new EventDto object from a request
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->title,
            start_time: Carbon::parse($request->start_time),
            end_time: $request->end_time ? Carbon::parse($request->end_time) : null
        );
    }

    /**
     * Convert the object into readable array format
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            "title" => $this->title,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time
        ];
    }
}

```

#### 6. App\Events\CalendarUpdatedEvent.php
```php
class CalendarUpdatedEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * No need to register events in ServiceProviders
     * You can just add them to the array here
     * And it will automatically register them as listeners
     *
     * To understand how this works, checkout the BaseEvent parent class
     * @see \App\Events\BaseEvent
     */
    protected array $listeners = [
        UpdateEventListListener::class
    ];

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        parent::__construct();
    }
}

```
The **_CalendarUpdatedEvent_** has **_App\Listeners\UpdateEventListListener_** attached to it. 

The **_UpdateEventListListener_** deletes the previous event list cache and updates it with the latest value.
```php
class UpdateEventListListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Cache::forget("events");
        Cache::set("events", (new CalendarService)->getEvents());
    }
}

```

This listener class implements the **_ShouldQueue_** interface. This was done as getting a list of events from the calendar api takes time (around 2-3 seconds).

This class also extends the **_BaseEvent_** class. This **_BaseEvent_** class allows us to register the listener in our event class rather than registering it in ServiceProviders.
