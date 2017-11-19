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

Route::post('/login', 'LoginController@login');
Route::post('/verify', 'LoginController@verify');

Route::post('/refresh', 'LoginController@refresh');

Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
    Route::get('/user', 'UserController@index');
});
