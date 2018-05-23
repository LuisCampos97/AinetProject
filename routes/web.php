<?php

USE Illuminate\Support\Facades\Input;
USE App\User;

//User
Route::get('/', 'WelcomeController@home');
 
Route::get('/users', 'UserController@index')->name('users');
Auth::routes();

Route::get('/me/dashboard', 'HomeController@index')->name('home');

//Edit
Route::get('/me/profile', 'UserController@edit')->name('edit');
Route::put('/me/dashboard', 'UserController@update')->name('update');

//Edit Password
Route::get('/me/password', 'UserController@editPassword')->name('editPassword');
Route::patch('/me/dashboard', 'UserController@updatePassword')->name('updatePassword');

//Block/Unblock/Promote/Demote
Route::patch('/users/{user}/block', 'UserController@block')->name('users.block');
Route::patch('/users/{user}/unblock', 'UserController@unblock')->name('users.unblock');
Route::patch('/users/{user}/promote', 'UserController@promote')->name('users.promote');
Route::patch('/users/{user}/demote', 'UserController@demote')->name('users.demote');

//Profile
Route::get('/profiles', 'UserController@profiles')->name('profiles');
Route::get('/me/associate-of', 'UserController@associateOf')->name('associateOf');
Route::get('/me/associates', 'UserController@associates')->name('associates');
Route::delete('/me/associates/{user}', 'UserController@deleteAssociate')->name('associate.delete');
Route::get('/profiles/search', 'UserController@searchNameProfiles')->name('profiles.searchNameProfiles');

Route::post('/me/associates', 'UserController@storeAssociate')->name('associate.store');
Route::get('/ola', 'UserController@addAssociate')->name('associate.add'); //Modificar nome

//Accounts
Route::get('/accounts/{user}', 'UserController@accountsForUser')->name('usersAccount');
Route::get('/accounts/{user}/opened', 'UserController@openedAccounts')->name('openedAccounts');
Route::get('/accounts/{user}/closed', 'UserController@closedAccounts')->name('closedAccounts');
Route::delete('/account/{account}', 'UserController@destroy')->name('deleteAccount');
Route::patch('/account/{account}/close', 'UserController@closeAccount')->name('closeAccount');
Route::patch('/account/{account}/reopen', 'UserController@openAccount')->name('openAccount');

//Movements of Account
Route::get('/movements/{account}', 'UserController@showMovementsForAccount')->name('movementsForAccount');

//Create account
Route::get('/account', 'UserController@createAccount')->name('createAccount');
Route::post('/account', 'UserController@storeAccount')->name('storeAccount');

Route::get('/account/{account}', 'UserController@updateAccountView')->name('updateAccountView');
Route::put('/updateAccount', 'UserController@updateAccount')->name('updateAccount');

//Movements
Route::get('/movements/{account}/create', 'UserController@viewCreateMovement')->name('viewCreateAccount');
Route::post('/movements/{account}/create', 'UserController@storeMovement')->name('storeMovement');
Route::delete('/account/{account}/{movement}', 'UserController@deleteMovement')->name('deleteMovement');