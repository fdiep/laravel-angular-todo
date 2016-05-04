<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
  Route::get('/', function () {
    return view('app');
  });
});

// API Routes
Route::group(['prefix' => 'api/v1', 'middleware' => ['api']], function () {
  // Auth Routes
  Route::post('/register', 'TokenAuthController@register');
  Route::post('/authenticate', 'TokenAuthController@authenticate');
  // Secured routes
  Route::group(['middleware' => ['custom.jwt.auth']], function () {
    Route::get('/authenticate/user', 'TokenAuthController@getAuthenticatedUser');
    // Todo Routes
    Route::resource('/todo', 'TodoController');
  });
});
