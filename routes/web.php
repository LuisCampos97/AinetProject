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

//Edit Password
Route::get('/me/password', 'UserController@editPassword')->name('editPassword');
Route::patch('/home', 'UserController@updatePassword')->name('updatePassword');

<<<<<<< HEAD
//List users
Route::get('/', 'SearchController@index')->name('search');
=======
//Block
Route::patch('/users/{user}/block', 'UserController@block');
>>>>>>> b8d81beb139375ee24c37ff9badc16367db68658
