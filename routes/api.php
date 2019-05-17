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
    Route::get('/', 'Cerevids\CourseController@index')->name('courses');
    Route::post('/create', 'Cerevids\CourseController@create')->name('course/create');
    Route::get('/{id}', 'Cerevids\CourseController@find')->name('course/detail');
    Route::put('/{id}', 'Cerevids\CourseController@update')->name('course/update');
    Route::delete('/{id}', 'Cerevids\CourseController@delete')->name('course/delete');

    Route::group(['prefix' => '/{course_id}/reviews'], function(){
        Route::get('/', 'Cerevids\ReviewController@index')->name('reviews');
        Route::post('/create', 'Cerevids\ReviewController@create')->name('review/create');
        Route::get('/{review_id}', 'Cerevids\ReviewController@find')->name('review/detail');
        Route::put('/{review_id}', 'Cerevids\ReviewController@update')->name('review/update');
        Route::delete('/{review_id}', 'Cerevids\ReviewController@delete')->name('review/delete');
    });

    Route::group(['prefix' => '/{course_id}/forums'], function(){
        Route::get('/', 'Cerevids\ForumController@index')->name('forums');
        Route::post('/student_create', 'Cerevids\ForumController@createForStudent')->name('forum/student_create');
        Route::post('/teacher_create', 'Cerevids\ForumController@createForTeacher')->name('forum/teacher_create');
        Route::get('/{forum_id}', 'Cerevids\ForumController@find')->name('forum/detail');
        Route::put('/{forum_id}', 'Cerevids\ForumController@update')->name('forum/update');
        Route::delete('/{forum_id}', 'Cerevids\ForumController@delete')->name('forum/delete');
    });

    Route::group(['prefix' => '/{course_id}/cerevids'], function(){
        Route::get('/', 'Cerevids\CerevidController@index')->name('cerevids');
        Route::post('/create', 'Cerevids\CerevidController@create')->name('cerevid/create');
        Route::get('/{cerevid_id}', 'Cerevids\CerevidController@find')->name('cerevid/detail');
        Route::delete('/{cerevid_id}', 'Cerevids\CerevidController@delete')->name('cerevid/delete');
    });

    Route::group(['prefix' => '/{course_id}/favorites'], function(){
        Route::get('/', 'Cerevids\FavoriteController@index')->name('favorites');
        Route::post('/create', 'Cerevids\FavoriteController@create')->name('favorite/create');
        Route::get('/{favorite_id}', 'Cerevids\FavoriteController@find')->name('favorite/detail');
        Route::delete('/{favorite_id}', 'Cerevids\FavoriteController@delete')->name('favorite/delete');
    });
});