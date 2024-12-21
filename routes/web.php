<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('HomePage');
});

Route::get('/about', function () {
    return inertia('AboutPage');
});

Route::fallback(function () {
    return inertia('NotFound');
});
