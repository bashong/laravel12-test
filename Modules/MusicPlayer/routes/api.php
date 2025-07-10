<?php

use Illuminate\Support\Facades\Route;
use Modules\MusicPlayer\Http\Controllers\MusicPlayerController;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::prefix('music-player')->group(function() {
        Route::get('/play/{song}', [MusicPlayerController::class, 'play']);
        Route::get('/next/{order}', [MusicPlayerController::class, 'next'])->whereIn('category', ['rand', 'asc']);
        Route::get('/previous', [MusicPlayerController::class, 'previous']);
        Route::get('/shuffle', [MusicPlayerController::class, 'shuffle']);
        Route::get('/queue', [MusicPlayerController::class, 'queue']);
    });
});