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

Route::middleware('auth:api')->group(function() {
    Route::get('/messages', 'MessagesController@index');
    Route::get('/messages/deleted', 'MessagesController@indexDeleted');
    Route::post('/messages', 'MessagesController@store');
    Route::get('/messages/{message}', 'MessagesController@show');
    Route::patch('/messages/{message}', 'MessagesController@update');
    Route::patch('/messages/{message}/restore', 'MessagesController@restore');
    Route::delete('/messages/{message}', 'MessagesController@destroy');
    Route::delete('/messages/{message}/force', 'MessagesController@forceDestroy');
});