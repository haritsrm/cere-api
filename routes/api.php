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


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        //get profile
        Route::get('user', 'AuthController@user');
        //change profile
        Route::put('user/{id}', 'AuthController@changeProfile');
        //change avatar
        Route::post('changePhotoProfile/{id}', 'AuthController@changePhotoProfile');
        //get avatar
        Route::get('photoProfile/{id}', 'AuthController@getPhotoProfile');
    });
});

Route::group([    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
	//create token 
    Route::post('create', 'PasswordResetController@create');
    //find token 
    Route::get('find/{token}', 'PasswordResetController@find');
    //reset password
    Route::post('reset', 'PasswordResetController@reset');
});

Route::group(['prefix' => 'courses'], function(){
    Route::get('/', 'CourseController@index')->name('courses');
    Route::post('/create', 'CourseController@create')->name('course/create');
    Route::get('/{id}', 'CourseController@find')->name('course/detail');
    Route::put('/{id}', 'CourseController@update')->name('course/update');
    Route::delete('/{id}', 'CourseController@delete')->name('course/delete');

    Route::group(['prefix' => '/{course_id}/reviews'], function(){
        Route::get('/', 'ReviewController@index')->name('reviews');
        Route::post('/create', 'ReviewController@create')->name('review/create');
        Route::get('/{review_id}', 'ReviewController@find')->name('review/detail');
        Route::put('/{review_id}', 'ReviewController@update')->name('review/update');
        Route::delete('/{review_id}', 'ReviewController@delete')->name('review/delete');
    });
});