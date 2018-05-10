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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'WelcomeController@home');
 
Route::get('/users', 'UserController@index')->name('users');
Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

//Edit
Route::get('/me/profile', 'UserController@edit')->name('edit');
Route::put('/home', 'UserController@update')->name('update');

<<<<<<< HEAD
//List users
Route::get('/', 'SearchController@index')->name('search');
=======
Route::post('/me/password', 'UserController@editPassword')->name('editPassword');
Route::patch('/home', 'UserController@updatePassword')->name('updatePassword');
>>>>>>> f33f518b17c0cc903c8cfb35ba42eb5ffe84a27f
