<?php

use App\Web\StreamSuggestions\Controllers\RetryStreamSuggestionController;
use App\Web\StreamSuggestions\Controllers\StreamSuggestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(StreamSuggestionController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::post('', 'store')->name('store');
    Route::put('', 'update')->name('update');
    Route::get('{streamSuggestion}', 'show')->name('show');
    Route::put('{streamSuggestion}/retry', RetryStreamSuggestionController::class)->name('retry');
});
