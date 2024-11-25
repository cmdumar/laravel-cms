<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\MediaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('pages', PageController::class);

    // Media routes
    Route::get('media', [MediaController::class, 'index']);
    Route::post('media', [MediaController::class, 'store']);
    Route::get('media/{id}', [MediaController::class, 'show']);
    Route::delete('media/{id}', [MediaController::class, 'destroy']);
    Route::get('media/slug/{slug}', [MediaController::class, 'getBySlug']);
    Route::post('media/{id}/slug', [MediaController::class, 'addSlug']);
    Route::post('pages/{id}/media', [PageController::class, 'attachMedia']);
    Route::delete('pages/{id}/media', [PageController::class, 'detachMedia']);
});
