<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('os')->group(function () {
    Route::get('/', 'ApiController@getOperatingSystems')->name('api.oss.get');
    Route::get('/{id}', 'ApiController@getOperatingSystem')->name('api.os.get');
});

Route::prefix('template')->group(function () {
    Route::get('/', 'ApiController@getTemplates')->name('api.templates.get');
    Route::post('/', 'ApiController@storeTemplate')->name('api.template.post');
    Route::get('/{id}', 'ApiController@getTemplate')->name('api.template.get');
    Route::delete('/{id}', 'ApiController@delTemplate')->name('api.template..del');
});

Route::prefix('network')->group(function () {
    Route::get('/', 'ApiController@getNetworks')->name('api.networks.get');
    Route::post('/', 'ApiController@storeNetwork')->name('api.network.post');
    Route::get('/{id}', 'ApiController@getNetwork')->name('api.network.get');
    Route::delete('/{id}', 'ApiController@delNetwork')->name('api.network.del');
});

Route::prefix('spec')->group(function () {
    Route::get('/', 'ApiController@getSpecs')->name('api.specs.get');
    Route::post('/', 'ApiController@storeSpec')->name('api.spec.post');
    Route::get('/{id}', 'ApiController@getSpec')->name('api.spec.get');
    Route::delete('/{id}', 'ApiController@delSpec')->name('api.spec.del');
    Route::get('/session/{token}', 'ApiController@getSpecFromToken')->name('api.spec.session.token.get');

});

Route::prefix('session')->group(function () {
    Route::post('/request', 'ApiController@requestSession')->name('api.session.request.post');
    Route::post('/run', 'ApiController@runSession')->name('api.session.run.post');
});
