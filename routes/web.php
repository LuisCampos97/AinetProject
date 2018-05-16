<?php

USE Illuminate\Support\Facades\Input;
USE App\User;

Route::get('/', function () {
    if(request()->has('type')){
        $users= App\User::where('type', request('type'))->paginate(10)->appends('type', request('type'));
    }
    else{
        $users = App\User::paginate(10);
    }
    if(request()->has('status')){
        $users= App\User::where('status', request('status'))->paginate(10)->appends('status', request('status'));
    }
    else{
        $users = App\User::paginate(10);
    }


    return view('users')->with('users', $users);
});

Route::get('/', 'WelcomeController@home');
 
Route::get('/users', 'UserController@index')->name('users');
Route::get('/users/search', 'UserController@search')->name('users.search');
/*Route::get('/users', function(){
    $q= Input::get('q');
    if($q!=""){
        $user = User::where('name','LIKE', '%'. $q . '%')
                            ->get();
        if(count($user) >0 ){
            return view('welcome');
        }
    }
    return "No users found!";

})->name('users'); */
Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

//Edit
Route::get('/me/profile', 'UserController@edit')->name('edit');
Route::put('/home', 'UserController@update')->name('update');

//Edit Password
Route::get('/me/password', 'UserController@editPassword')->name('editPassword');
Route::patch('/home', 'UserController@updatePassword')->name('updatePassword');

//Block/Unblock/Promote/Demote
Route::patch('/users/{user}/block', 'UserController@block')->name('users.block');
Route::patch('/users/{user}/unblock', 'UserController@unblock')->name('users.unblock');
Route::patch('/users/{user}/promote', 'UserController@promote')->name('users.promote');
Route::patch('/users/{user}/demote', 'UserController@demote')->name('users.demote');

//Profile
Route::get('/profiles', 'UserController@profiles')->name('profiles');
Route::get('/me/associate-of', 'UserController@associateOf')->name('associateOf');
Route::get('/me/associates', 'UserController@associates')->name('associates');

//Accounts
Route::get('/accounts/{user}', 'UserController@accountsForUser')->name('usersAccount');
Route::get('/accounts/{user}/opened', 'UserController@openedAccounts')->name('openedAccounts');
Route::get('/accounts/{user}/closed', 'UserController@closedAccounts')->name('closedAccounts');
Route::delete('/account/{account}', 'UserController@destroy')->name('deleteAccount');
Route::patch('/account/{account}/close', 'UserController@closeAccount')->name('closeAccount');
Route::patch('/account/{account}/open', 'UserController@openAccount')->name('openAccount');

//Movements of Account
Route::get('/movements/{account}', 'UserController@showMovementsForAccount')->name('movementsForAccount');
