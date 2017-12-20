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

Route::get('/', function() {
    return response()->json([
        'success' => 1,
        'message' => 'API working..'
    ]);
});

Route::post('login', 'LoginController@login');
Route::post('verify', 'LoginController@verify');
Route::post('refresh', 'LoginController@refresh');

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::post('demo/add-balance', 'DemoController@addBalance');

    Route::group(['prefix' => 'user'], function() {
        Route::get('/', 'UserController@index');
        Route::get('activity', 'UserController@activity');
        Route::get('balance', 'UserController@balance');
    });

    Route::post('/park', 'ParkController@park');
    Route::post('/unpark', 'ParkController@unpark');
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'parking'], function() {
        Route::get('/', 'ParkingController@index');
        Route::get('search', 'ParkingController@search');
        Route::post('entry-qrcode', 'ParkingController@entryQrCode');
        Route::post('exit-qrcode', 'ParkingController@exitQrCode');
        Route::get('{id}', 'ParkingController@parking');
    });
});
