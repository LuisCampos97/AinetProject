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
Route::get('/profiles', 'ProfileController@profiles')->name('profiles');
Route::get('/me/associate-of', 'ProfileController@associateOf')->name('associateOf');
Route::get('/me/associates', 'ProfileController@associates')->name('associates');
Route::delete('/me/associates/{user}', 'ProfileController@deleteAssociate')->name('associate.delete');
Route::post('/me/associates', 'ProfileController@storeAssociate')->name('associate.store');
Route::get('/ola', 'ProfileController@addAssociate')->name('associate.add'); //Modificar nome

//Accounts
Route::get('/accounts/{user}', 'AccountController@accountsForUser')->name('usersAccount');
Route::get('/accounts/{user}/opened', 'AccountController@openedAccounts')->name('openedAccounts');
Route::get('/accounts/{user}/closed', 'AccountController@closedAccounts')->name('closedAccounts');
Route::delete('/account/{account}', 'AccountController@destroy')->name('deleteAccount');
Route::patch('/account/{account}/close', 'AccountController@closeAccount')->name('closeAccount');
Route::patch('/account/{account}/reopen', 'AccountController@openAccount')->name('openAccount');

//Movements of Account
Route::get('/movements/{account}', 'AccountController@showMovementsForAccount')->name('movementsForAccount');

//Create account
Route::get('/account', 'AccountController@createAccount')->name('createAccount');
Route::post('/account', 'AccountController@storeAccount')->name('storeAccount');

Route::get('/account/{account}', 'AccountController@updateAccountView')->name('updateAccountView');
Route::put('/updateAccount', 'AccountController@updateAccount')->name('updateAccount');

//Movements
Route::get('/movements/{account}/create', 'MovementController@viewCreateMovement')->name('viewCreateAccount');
Route::post('/movements/{account}/create', 'MovementController@storeMovement')->name('storeMovement');
Route::delete('/account/{account}/{movement}', 'MovementController@deleteMovement')->name('deleteMovement');