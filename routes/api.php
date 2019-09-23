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

Route::middleware('auth:api')->get('/users', 'Api\UsersController@index')->name('users.index');

Route::middleware('auth:api')->post('/@me/channel/{user}', 'MessagesController@store')->name('messages.store');

Route::patch('/users/{user}/activation', 'AdminController@setUserActiveness')->name('admin.activate')->middleware(['auth:api', 'can:is-admin']);

Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy')->middleware(['auth:api', 'can:is-admin']);

Route::delete('/images/delete', 'ImageController@destroy')->name('images.destroy')->middleware(['auth:api']); //'can:image-delete'

Route::get('/annonces', 'AnnoncesController@index')->name('annonces.index');
Route::post('/annonces', 'AnnoncesController@store')->name('annonces.store');


Route::group(['middleware' => ['auth:api', 'can:is-active']], function () {
    Route::get('/conversations', 'Api\ConversationsController@index');
    Route::get('/conversation/{user}', 'Api\ConversationsController@show');
    Route::post('/conversations/{user}', 'Api\ConversationsController@store');
    Route::patch('/messages/{message}', 'Api\MessagesController@read')->middleware(['can:read,message']);
});
