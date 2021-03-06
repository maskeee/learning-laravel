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

Route::get('/','StaticPagesController@home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');
Route::get('/signup','UserController@create')->name('signup');
Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');

Route::resource('users','UserController');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

Route::get('password/email','Auth\PasswordController@getEmail')->name('password.reset');
Route::post('password/email','Auth\PasswordController@postEmail')->name('password.reset');
Route::get('password/reset/{token}','Auth\PasswordController@getReset')->name('password.edit');
Route::post('password/reset','Auth\PasswordController@postReset')->name('password.update');

Route::resource('statuses','StatusController',['only'=>['store','destroy']]);

Route::get('/users/{id}/followings', 'UserController@followings')->name('users.followings');
Route::get('/users/{id}/followers', 'UserController@followers')->name('users.followers');

Route::post('/user/follower/{id}','FollowerController@store')->name('follower.store');
Route::delete('/user/follower/{id}','FollowerController@destroy')->name('follower.destroy');