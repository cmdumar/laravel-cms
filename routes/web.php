<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/media/{filename}', function ($filename) {
    $path = "/share/media/{$filename}";
    if (!file_exists($path)) {
        return Response::make('File not found.', 404);
    }
    return Response::file($path);
});
