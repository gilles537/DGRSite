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
    return view('HomePage');
});

Route::post('/posts/{id}/like', 'PostsController@like');
Route::resource('posts' , 'PostsController');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

