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



Route::get('/users', 'UserController@index')->name('users.index');
Route::get('/users/{user}', 'UserController@show')->name('users.show');
Route::patch('/users/{user}', 'UserController@update')->name('users.update')->middleware('can:update-user,user');
Route::patch('/users/{user}/password', 'UserController@updatePassword')->name('users.update.password')->middleware('can:update-user,user');

Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('can:is-admin');

Route::patch('/users/{user}/activation', 'AdminController@setUserActiveness')->name('admin.activate')->middleware(['auth', 'can:is-admin']);
