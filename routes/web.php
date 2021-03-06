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


Route::get('posts/old', 'PostsController@index_old');
Route::put('/posts/{id}/comment','PostsController@comment');
Route::get('/posts/{id}/like',['uses' => 'PostsController@like' , 'as' => 'like']);
Route::get('/posts/{id}/dislike',['uses' => 'PostsController@dislike' , 'as' => 'dislike']);
Route::resource('posts' , 'PostsController');
Route::get('/changePassword',['uses' => 'HomeController@showChangePasswordForm']);
Route::post('/changePassword','HomeController@changePassword')->name('changePassword');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

