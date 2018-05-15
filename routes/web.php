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

//Block
Route::patch('/users/{user}/block', 'UserController@block')->name('users.block');

//Profile
Route::get('/profiles', 'UserController@profiles')->name('profiles');
Route::get('/me/associate-of', 'UserController@associateOf')->name('associateOf');

//Accounts
Route::get('/accounts/{user}', 'UserController@accountsForUser')->name('usersAccount');
Route::get('/accounts/{user}/opened', 'UserController@openedAccounts')->name('openedAccounts');
Route::get('/accounts/{user}/closed', 'UserController@closedAccounts')->name('closedAccounts');
Route::delete('/account/{account}', 'UserController@deleteAccount')->name('deleteAccount');