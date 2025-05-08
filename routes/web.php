<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the House Management API',
        'version' => '1.0.0',
    ]);
});
