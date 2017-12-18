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

Route::get('/', function () {
    return "Nothing to check here..";
});

Route::group(['prefix' => 'admin', 'as' => 'admin:', 'namespace' => 'Admin'], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login:post');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin:', 'namespace' => 'Admin'], function () {
    Route::get('/', 'HomeController@index')->name('dashboard');

    Route::group(['prefix' => 'group', 'as' => 'group:'], function() {
        Route::get('/', 'GroupController@index')->name('index');
        Route::get('new', 'GroupController@new')->name('new');
        Route::post('create', 'GroupController@create')->name('create');
        Route::get('view/{id}', 'GroupController@view')->name('view');
        Route::get('edit/{id}', 'GroupController@edit')->name('edit');
        Route::post('update/{id}', 'GroupController@update')->name('update');
    });

    Route::group(['prefix' => 'parking', 'as' => 'parking:'], function() {
        Route::get('/', 'ParkingController@index')->name('index');
        Route::get('new', 'ParkingController@new')->name('new');
        Route::post('create', 'ParkingController@create')->name('create');
        Route::get('view/{id}', 'ParkingController@view')->name('view');
        Route::get('edit/{id}', 'ParkingController@edit')->name('edit');
        Route::post('update/{id}', 'ParkingController@update')->name('update');
    });

    Route::group(['prefix' => 'parking-level', 'as' => 'parking-level:'], function() {
        Route::get('/', 'ParkingLevelController@index')->name('index');
        Route::get('new/{parkingId}', 'ParkingLevelController@new')->name('new');
        Route::post('create/{parkingId}', 'ParkingLevelController@create')->name('create');
        Route::get('view/{id}', 'ParkingLevelController@view')->name('view');
        Route::get('edit/{id}', 'ParkingLevelController@edit')->name('edit');
        Route::post('update/{id}', 'ParkingLevelController@update')->name('update');
    });

    Route::group(['prefix' => 'user', 'as' => 'user:'], function() {
        Route::get('/', 'UserController@index')->name('index');
        Route::get('view/{id}', 'UserController@view')->name('view');
        Route::get('edit/{id}', 'UserController@edit')->name('edit');
        Route::post('update/{id}', 'UserController@update')->name('update');
    });
});
