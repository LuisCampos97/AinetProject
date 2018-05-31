<?php
 
use Illuminate\Support\Facades\Input;
 
//User
Route::get('/', 'WelcomeController@home');
 
Route::get('/users', 'UserController@index')->name('users');
Auth::routes();
 
Route::get('/dashboard/{user}', 'HomeController@index')->name('home')
    ->middleware('can:owner,user');

//Edit
Route::get('/me/profile/edit', 'UserController@edit')->name('edit');
Route::put('/me/profile', 'UserController@update')->name('update');
 
//Edit Password
Route::get('/me/password', 'UserController@editPassword')->name('editPassword');
Route::patch('/me/password', 'UserController@updatePassword')->name('updatePassword');
 
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
Route::get('/addAssociate', 'ProfileController@addAssociate')->name('associate.add'); //Modificar nome
 
//Accounts
Route::get('/accounts/{user}', 'AccountController@accountsForUser')->name('usersAccount')
    ->middleware('can:associate,user');

Route::get('/accounts/{user}/opened', 'AccountController@openedAccounts')->name('openedAccounts')
    ->middleware('can:associate,user');

Route::get('/accounts/{user}/closed', 'AccountController@closedAccounts')->name('closedAccounts')
    ->middleware('can:associate,user');

Route::delete('/account/{account}', 'AccountController@destroy')->name('deleteAccount')
    ->middleware('can:change-account,account');
    
Route::patch('/account/{account}/close', 'AccountController@closeAccount')->name('closeAccount')
    ->middleware('can:change-account,account');
    
Route::patch('/account/{account}/reopen', 'AccountController@openAccount')->name('openAccount')
    ->middleware('can:change-account,account');
 
//Movements of Account
Route::get('/movements/{account}', 'AccountController@showMovementsForAccount')->name('movementsForAccount')
    ->middleware('can:view-movements,account');
 
//Create account
Route::get('/account', 'AccountController@createAccount')->name('createAccount');
Route::post('/account', 'AccountController@storeAccount')->name('storeAccount');
 
Route::get('/account/{account}', 'AccountController@updateAccountView')->name('updateAccountView')
    ->middleware('can:change-account,account');
Route::put('/account/{account}', 'AccountController@updateAccount')->name('updateAccount')
    ->middleware('can:change-account,account');
 
//Movements
Route::get('/movements/{account}/create', 'MovementController@viewCreateMovement')->name('viewCreateAccount')
    ->middleware('can:change-movement,account');
Route::post('/movements/{account}/create', 'MovementController@storeMovement')->name('storeMovement')
    ->middleware('can:change-movement,account');
Route::delete('/account/{account}/{movement}', 'MovementController@deleteMovement')->name('deleteMovement')
    ->middleware('can:change-movement,account');
Route::get('/account/{account}/{movement}', 'MovementController@renderViewUpdateMovement')->name('viewUpdateMovement');
Route::put('/account/{account}/{movement}', 'MovementController@updateMovement')->name('updateMovement');

//Documents
Route::post('/documents/{movement}', 'DocumentController@uploadDocument')->name('uploadDocument')
->middleware('can:add-document,movement');;
Route::get('/documents/{movement}', 'DocumentController@uploadDocumentView')->name('uploadDocumentView')
->middleware('can:add-document,movement');;
