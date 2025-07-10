<?php

use Illuminate\Support\Facades\Route;
use Modules\MusicPlayer\Http\Controllers\SongController;

Route::middleware('auth','verified')->group(function () {
    Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
});