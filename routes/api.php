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

Route::get('/test','TeacherController@index');
Route::get('/testCreate','TeacherController@create');
Route::get('/testDestroy','TeacherController@destroy');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    //change password
    Route::post('user/changePassword/{id}', 'AuthController@changePassword');
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

//Cerevid's Routes --begin
Route::group([
    'prefix' => 'courses',
    'middleware' => 'auth:api'
], function(){
    Route::get('/', 'Cerevids\CourseController@index')->name('courses');
    Route::get('/lesson/{lesson_id}', 'Cerevids\CourseController@indexByLesson')->name('coursesByLesson');
    Route::post('/create', 'Cerevids\CourseController@create')->name('course/create');
    Route::get('/{id}', 'Cerevids\CourseController@find')->name('course/detail');
    Route::put('/{id}', 'Cerevids\CourseController@update')->name('course/update');
    Route::delete('/{id}', 'Cerevids\CourseController@delete')->name('course/delete');

    Route::group([
        'prefix' => '/{course_id}/sections'
    ], function(){
        Route::get('/', 'Cerevids\SectionController@index')->name('sections');
        Route::post('/create', 'Cerevids\SectionController@create')->name('section/create');
        Route::get('/{section_id}', 'Cerevids\SectionController@find')->name('section/detail');
        Route::put('/{section_id}', 'Cerevids\SectionController@update')->name('section/update');
        Route::delete('/{section_id}', 'Cerevids\SectionController@delete')->name('section/delete');
    });

    Route::group([
        'prefix' => '/{course_id}/reviews'
    ], function(){
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

Route::group([
    'prefix' => 'sections/{section_id}',
    'middleware' => 'auth:api'
], function(){
    Route::get('/videos', 'Cerevids\VideoController@index')->name('videos');
    Route::post('/videos/create', 'Cerevids\VideoController@create')->name('video/create');
    Route::get('/videos/{video_id}', 'Cerevids\VideoController@find')->name('video/detail');
    Route::put('/videos/{video_id}', 'Cerevids\VideoController@update')->name('video/update');
    Route::delete('/videos/{video_id}', 'Cerevids\VideoController@delete')->name('video/delete');

    Route::get('/texts', 'Cerevids\TextController@index')->name('texts');
    Route::post('/texts/create', 'Cerevids\TextController@create')->name('text/create');
    Route::get('/texts/{text_id}', 'Cerevids\TextController@find')->name('text/detail');
    Route::put('/texts/{text_id}', 'Cerevids\TextController@update')->name('text/update');
    Route::delete('/texts/{text_id}', 'Cerevids\TextController@delete')->name('text/delete');

    Route::get('/quiz', 'Cerevids\QuizController@index')->name('quiz');
    Route::post('/quiz/create', 'Cerevids\QuizController@create')->name('quiz/create');
    Route::get('/quiz/{video_id}', 'Cerevids\QuizController@find')->name('quiz/detail');
    Route::put('/quiz/{video_id}', 'Cerevids\QuizController@update')->name('quiz/update');
    Route::delete('/quiz/{video_id}', 'Cerevids\QuizController@delete')->name('quiz/delete');
});
//Cerevid's Routes --end

//Cereout's Routes --begin
Route::group(['prefix' => 'cereouts', 'middleware' => 'auth:api'], function(){
    Route::get('/question/{id}', 'Cereouts\QuestionController@index')->name('questions');
    Route::get('/', 'Cereouts\TryoutController@index')->name('tryouts');
    Route::post('/create', 'Cereouts\TryoutController@create')->name('tryout/create');
    Route::get('/{id}', 'Cereouts\TryoutController@find')->name('tryout/detail');
    Route::put('/{id}', 'Cereouts\TryoutController@update')->name('tryout/update');
    Route::delete('/{id}', 'Cereouts\TryoutController@delete')->name('tryout/delete');

    Route::group(['prefix' => '/{tryout_id}/attempts'], function(){
        Route::get('/', 'Cereouts\CereoutController@index')->name('cereouts');
        Route::get('/mine', 'Cereouts\CereoutController@indexByUser')->name('cereoutsByUser');
        Route::get('/rankings', 'Cereouts\CereoutController@ranking')->name('cereout/ranking');
        Route::post('/', 'Cereouts\CereoutController@attempt')->name('cereout/attempt');
        Route::get('/{id}', 'Cereouts\CereoutController@find')->name('cereout/detail');
        Route::post('/{id}/valuation', 'Cereouts\CereoutController@valuation')->name('cereout/valuation');
        Route::delete('/{id}', 'Cereouts\CereoutController@delete')->name('cereout/delete');
    });
});
//Cereout's Routes --end

//master data Routes
Route::group(['prefix' => 'master'], function(){
    Route::get('/class', 'Master\ClassController@index');
    Route::get('/lesson', 'Cerevids\EnvironmentController@lessons');
    Route::get('/university', 'Master\UniversityController@index');
    Route::get('/department', 'Master\DepartmentController@index');
    Route::get('/faculty', 'Master\FacultyController@index');
    Route::get('/information', 'Master\InformationController@index');
    Route::get('/generalInformation', 'Master\GeneralInformationController@index');
});