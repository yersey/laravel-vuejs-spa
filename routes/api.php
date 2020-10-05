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
Route::get('mirko/{entry}', 'MirkoController@show');
Route::post('mirko', 'MirkoController@store')->middleware('jwt.auth');
Route::put('mirko/{entry}', 'MirkoController@update')->middleware('jwt.auth');
Route::delete('mirko/{entry}', 'MirkoController@destroy')->middleware('jwt.auth');

//Post
Route::get('post', 'PostController@index');
Route::get('post/{post}', 'PostController@show');
Route::post('post', 'PostController@store')->middleware('jwt.auth');
Route::put('post/{post}', 'PostController@update')->middleware('jwt.auth');
Route::delete('post/{post}', 'PostController@destroy')->middleware('jwt.auth');

//Dig
Route::post('post/dig/{post}', 'PostController@dig')->middleware('jwt.auth');
Route::delete('post/dig/{post}', 'PostController@unDig')->middleware('jwt.auth');

//Comment
Route::get('comment/{comment}', 'CommentController@show');
Route::post('comment', 'CommentController@store')->middleware('jwt.auth');
Route::put('comment/{comment}', 'CommentController@update')->middleware('jwt.auth');
Route::delete('comment/{comment}', 'CommentController@destroy')->middleware('jwt.auth');

//plus
Route::post('entry/plus/{entry}', 'MirkoController@plus')->middleware('jwt.auth');
Route::delete('entry/plus/{entry}', 'MirkoController@unPlus')->middleware('jwt.auth');
Route::post('comment/plus/{comment}', 'CommentController@plus')->middleware('jwt.auth');
Route::delete('comment/plus/{comment}', 'CommentController@unPlus')->middleware('jwt.auth');

//notifications
Route::get('notifications', 'UserController@notifications')->middleware('jwt.auth');
Route::get('notification/{id}/markasread', 'UserController@notification_mark_as_read')->middleware('jwt.auth');

//messages
Route::get('conversations', 'MessageController@conversations')->middleware('jwt.auth');
Route::get('conversation/{conversation_id?}', 'MessageController@conversation')->middleware('jwt.auth');
Route::post('message', 'MessageController@store')->middleware('jwt.auth');

//profile
Route::get('user/{user}', 'ProfileController@show');
Route::get('user/{user}/posts', 'ProfileController@posts');
Route::get('user/{user}/entries', 'ProfileController@entries');
Route::get('user/{user}/tags', 'ProfileController@tags');

//tag
Route::get('tags', 'TagController@index');
Route::get('tag/{tag}', 'TagController@show');
Route::post('tag/{tag}/follow', 'TagController@follow')->middleware('jwt.auth');
Route::delete('tag/{tag}/unfollow', 'TagController@unfollow')->middleware('jwt.auth');

//settings
Route::post('settings/avatar', 'SettingsController@uploadAvatar')->middleware('jwt.auth');
