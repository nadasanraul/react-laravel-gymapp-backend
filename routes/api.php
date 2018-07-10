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

Route::group(['middleware' => 'auth.jwt'], function() {
    //Exercises routes
    Route::apiResource('exercises', 'ExerciseController');

    //Categories routes
    Route::apiResource('categories', 'CategoryController');

    //Sets routes
    Route::apiResource('sets', 'SetController');
});

//Workout routes
Route::get('/workout/{day}', 'WorkoutController@show');
Route::get('/workouts', 'WorkoutController@index');

//Auth routes
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');