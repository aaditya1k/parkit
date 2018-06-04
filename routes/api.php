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

Route::group(['as' => 'api:'], function () {
    Route::get('/', function () {
        return response()->json([
            'success' => true,
            'message' => "Everything up & running.. 🏃"
        ]);
    })->name('index');

    Route::group(['as' => 'login:'], function () {
        Route::post('login', 'LoginController@login')->name('login');
        Route::post('verify', 'LoginController@verify')->name('verify');
        Route::post('refresh', 'LoginController@refresh')->name('refresh');
    });

    Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
        Route::post('demo/add-balance', 'DemoController@addBalance')->name('demo:balance');

        Route::group(['prefix' => 'user', 'as' => 'user:'], function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('activity', 'UserController@activity')->name('activity');
            Route::get('balance', 'UserController@balance')->name('balance');
        });

        Route::post('/park', 'ParkController@park')->name('park');
        Route::post('/unpark', 'ParkController@unpark')->name('unpark');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'parking', 'as' => 'parking:'], function () {
            Route::get('/', 'ParkingController@index')->name('index');
            Route::get('search', 'ParkingController@search')->name('search');
            Route::post('entry-qrcode', 'ParkingController@entryQrCode')->name('entry-qr');
            Route::post('exit-qrcode', 'ParkingController@exitQrCode')->name('exit-qr');
            Route::get('{id}', 'ParkingController@parking')->name('parking');
        });
    });
});
