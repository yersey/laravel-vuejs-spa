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
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::post('test', 'TestController@index');

//Mirko
Route::get('mirko', 'MirkoController@index');
Route::get('mirko/{id}', 'MirkoController@show');
Route::post('mirko', 'MirkoController@store');
Route::put('mirko/{id}', 'MirkoController@update');
Route::delete('mirko/{id}', 'MirkoController@destroy');

//Post
Route::get('post', 'PostController@index');
Route::get('post/{id}', 'PostController@show');
Route::post('post', 'PostController@store');
Route::put('post/{id}', 'PostController@update');
Route::delete('post/{id}', 'PostController@destroy');

//Wykop
Route::post('post/wykop/{id}', 'PostController@wykop');
Route::delete('post/wykop/{id}', 'PostController@unWykop');

//Comment
Route::get('comment/{id}', 'CommentController@show');
Route::post('comment', 'CommentController@store');
Route::put('comment/{id}', 'CommentController@update');
Route::delete('comment/{id}', 'CommentController@destroy');

//Plus
Route::post('plus', 'PlusController@store');
Route::delete('plus/{model}/{id}', 'PlusController@destroy');

//notifications
Route::get('notifications', 'UserController@notifications');
Route::get('notification/{id}/markasread', 'UserController@notification_mark_as_read');

//messages
Route::get('conversations', 'MessageController@conversations');
Route::get('conversation/{conversation_id?}', 'MessageController@conversation');
Route::post('message', 'MessageController@store');

//profile
//Route::get('users', 'MessageController@users'); lista userow
Route::get('user/{name}', 'ProfileController@show');
Route::get('user/{name}/posts', 'ProfileController@posts');
Route::get('user/{name}/wpisy', 'ProfileController@wpisy');
Route::get('user/{name}/tags', 'ProfileController@tags');

//tag
Route::get('tags', 'TagController@index');
Route::get('tag/{name}', 'TagController@show');
Route::post('tag/{name}/follow', 'TagController@follow');
Route::delete('tag/{name}/unfollow', 'TagController@unfollow');

//settings
Route::post('settings/avatar', 'SettingsController@uploadAvatar');