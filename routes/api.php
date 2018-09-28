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

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', 'Auth\LoginController@logout');

    Route::get('people', 'API\PersonController@index');
    Route::post('people', 'API\PersonController@store');
    Route::get('people/{personid}', 'API\PersonController@show');
    Route::put('people/{personid}', 'API\PersonController@update');
});
