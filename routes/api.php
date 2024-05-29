<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource("/events", EventController::class)->only(["index", "store", "destroy"]);
