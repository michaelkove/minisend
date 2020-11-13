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

    Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('home');
    Route::get('/documentation', 'App\Http\Controllers\DashboardController@doc')->name('doc');
    Route::post('/do-login', 'App\Http\Controllers\DashboardController@simulatedLogin')->name('login');
    Route::post('/logoff', 'App\Http\Controllers\DashboardController@simulatedLogoff')->name('logoff');
    Route::get('/dashboard/{any?}', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard')->middleware('auth'); //Expired users go home
