<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->middleware(['auth'])->name('dashboard');
Route::post('/send-message', 'App\Http\Controllers\DashboardController@sendmessage')->middleware(['auth'])->name('sendmessage');
Route::post('/load-previous-conversation', 'App\Http\Controllers\DashboardController@LoadMessages')->middleware(['auth'])->name('load_previous_messages');
Route::post('/delete-message', 'App\Http\Controllers\DashboardController@DeleteMessages')->middleware(['auth'])->name('delete-message');
Route::post('/get-messages', 'App\Http\Controllers\DashboardController@GetMessages')->middleware(['auth'])->name('get-message');

require __DIR__.'/auth.php';
