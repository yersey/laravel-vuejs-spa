<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout')->middleware('jwt.auth');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me')->middleware('jwt.auth');

});

Route::post('test', 'TestController@index');

//Mirko
Route::get('mirko', 'MirkoController@index');
Route::get('mirko/{id}', 'MirkoController@show');
Route::post('mirko', 'MirkoController@store')->middleware('jwt.auth');
Route::put('mirko/{id}', 'MirkoController@update')->middleware('jwt.auth');
Route::delete('mirko/{id}', 'MirkoController@destroy')->middleware('jwt.auth');

//Post
Route::get('post', 'PostController@index');
Route::get('post/{id}', 'PostController@show');
Route::post('post', 'PostController@store')->middleware('jwt.auth');
Route::put('post/{id}', 'PostController@update')->middleware('jwt.auth');
Route::delete('post/{id}', 'PostController@destroy')->middleware('jwt.auth');

//Dig
Route::post('post/dig/{id}', 'PostController@dig')->middleware('jwt.auth');
Route::delete('post/dig/{id}', 'PostController@unDig')->middleware('jwt.auth');

//Comment
Route::get('comment/{id}', 'CommentController@show');
Route::post('comment', 'CommentController@store')->middleware('jwt.auth');
Route::put('comment/{id}', 'CommentController@update')->middleware('jwt.auth');
Route::delete('comment/{id}', 'CommentController@destroy')->middleware('jwt.auth');

//Plus
Route::post('plus', 'PlusController@store')->middleware('jwt.auth');
Route::delete('plus/{model}/{id}', 'PlusController@destroy')->middleware('jwt.auth');

//notifications
Route::get('notifications', 'UserController@notifications')->middleware('jwt.auth');
Route::get('notification/{id}/markasread', 'UserController@notification_mark_as_read')->middleware('jwt.auth');

//messages
Route::get('conversations', 'MessageController@conversations')->middleware('jwt.auth');
Route::get('conversation/{conversation_id?}', 'MessageController@conversation')->middleware('jwt.auth');
Route::post('message', 'MessageController@store')->middleware('jwt.auth');

//profile
Route::get('user/{name}', 'ProfileController@show');
Route::get('user/{name}/posts', 'ProfileController@posts');
Route::get('user/{name}/entries', 'ProfileController@entries');
Route::get('user/{name}/tags', 'ProfileController@tags');

//tag
Route::get('tags', 'TagController@index');
Route::get('tag/{name}', 'TagController@show');
Route::post('tag/{name}/follow', 'TagController@follow')->middleware('jwt.auth');
Route::delete('tag/{name}/unfollow', 'TagController@unfollow')->middleware('jwt.auth');

//settings
Route::post('settings/avatar', 'SettingsController@uploadAvatar')->middleware('jwt.auth');