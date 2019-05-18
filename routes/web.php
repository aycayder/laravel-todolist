<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/admin', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('admin/user', 'UserController', ['except' => ['show']]);
	Route::get('admin/profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('admin/profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('admin/profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::resource('/tasks', 'TasksController');
});
