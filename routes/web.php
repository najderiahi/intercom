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



// Users
Auth::routes();
Route::get('/users', 'UserController@index')->name('users.index');
Route::get('/users/{user}', 'UserController@show')->name('users.show');
Route::patch('/users/{user}', 'UserController@update')->name('users.update')->middleware(['can:update,user']);
Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware(['can:view,user']);
Route::patch('/users/{user}/password', 'UserController@updatePassword')->name('users.update.password')->middleware(['can:update,user']);
Route::get('/users/{user}/password', 'UserController@showUpdatePasswordForm')->name('users.edit.password')->middleware(['can:update,user']);




Route::put('/annonce/{annonce}', 'AnnoncesController@update')->name('annonces.update')->middleware('can:update,annonce');

Route::delete('/annonce/{annonce}', 'AnnoncesController@destroy')->name('annonces.destroy')->middleware('can:delete,annonce');

Auth::routes();

Route::get('/feed', 'HomeController@index')->name('home');


//Admin

Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard')->middleware(['auth', 'can:is-admin']);
