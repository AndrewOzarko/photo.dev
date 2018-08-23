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

Route::get('/', 'PhotoController@index');



Route::group(['prefix' => 'photo'], function () {
    Route::get('upload', 'PhotoController@index');
    Route::post('upload', 'PhotoController@upload');
    Route::get('watermark', 'PhotoController@index');
    Route::post('watermark', 'PhotoController@watermark');
});