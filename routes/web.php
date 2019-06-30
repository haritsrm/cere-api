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
    return view('welcome');
});

// Social media route --begin
Route::get('login/{service}', 'AuthController@redirectToProvider');
Route::get('login/{service}/callback', 'AuthController@handleProviderCallback');
// Social media route --end

Route::get('password/find/{token}', 'PasswordResetController@find');
Route::post('password/reset', 'PasswordResetController@reset');