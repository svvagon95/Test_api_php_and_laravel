<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::apiResource('tasks', TaskController::class);

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});
