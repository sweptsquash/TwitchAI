<?php

use App\Web\Home\Controllers\HomeController;
use App\Web\Home\Controllers\RetryStreamSuggestion;
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

Route::get('', [HomeController::class, 'index'])->name('home');
Route::post('', [HomeController::class, 'store'])->name('store');
Route::put('', [HomeController::class, 'update'])->name('update');
Route::put('retry/{streamSuggestion}', RetryStreamSuggestion::class)->name('retry');
Route::get('{streamSuggestion}', [HomeController::class, 'show'])->name('show');
