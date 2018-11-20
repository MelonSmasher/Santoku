<?php

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


Route::get('/', 'ViewController@index')->name('index');
Route::get('/build', 'ViewController@build')->name('build');
Route::get('/session/{token}', 'ViewController@getSession')->name('session');