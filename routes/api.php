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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'course'], function(){
    Route::get('/', 'CourseController@index')->name('courses');
    Route::post('/create', 'CourseController@create')->name('course/create');
    Route::get('/{id}', 'CourseController@find')->name('course/detail');
    Route::put('/{id}', 'CourseController@update')->name('course/update');
    Route::delete('/{id}', 'CourseController@delete')->name('course/delete');
});