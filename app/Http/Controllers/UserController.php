<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');

    }

    public function block($id)
    {
        DB::table('users')
            ->where('users.id', '=', $id)
            ->update(['blocked' => 1]);

        return redirect()->action('UserController@index');
    }

    public function unblock($id)
    {
        DB::table('users')
            ->where('users.id', '=', $id)
            ->update(['blocked' => 0]);

        return redirect()->action('UserController@index');
    }


    public function promote($id)
    {
        DB::table('users')
            ->where('users.id', '=', $id)
            ->update(['admin' => 1]);
        
        return redirect()->action('UserController@index');
    }

    public function demote($id)
    {
        DB::table('users')
            ->where('users.id', '=', $id)
            ->update(['admin' => 0]);
        
        return redirect()->action('UserController@index');
    }

    public function edit(User $user)
    {
        $pagetitle = "Edit user";

        if (Auth::check()) {
            return view('users.edit', compact('pagetitle, user'));
        }
        return view('errors.user');

    }

    public function update(Request $request) //,$id

    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone' => 'min:3|max:12',
            'profile' => 'mimes:jpeg,png,jpg|max:1024',
        ], [ // Custom Messages
            'name.regex' => 'Name must only contain letters and spaces.',
        ]);

        $userModel = User::findOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'User Edited!']);
    }

    public function editPassword()
    {

        $pagetitle = 'Edit Password';
        if (Auth::check()) {
            return view('users.edit_password', compact('pagetitle, user'));
        }
        return view('errors.user');
    }

    public function updatePassword(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'password' => 'required|min:3|confirmed',
        ]);

        $user['password'] = Hash::make($request->password);

        $userModel = User::FindOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'Password Edited!']);

    }

    public function profiles(Request $request)
    {
        $name = $request->input('name');

        $users = User::all()->search($name);
        $users = DB::table('users')
            ->leftJoin('associate_members', 'users.id', '=', 'main_user_id')
            ->get();

        $associates = DB::table('associate_members')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of profiles';

        if (Auth::check()) {
            return view('users.profiles', compact('users', 'pagetitle', 'associates'));
        }
        return view('errors.user');   
    }

    public function associateOf()
    {
        $users = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'main_user_id')
            ->where('associate_members.associated_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of Associated-of profiles';

        if (Auth::check()) {
            return view('users.associateof', compact('users', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function accountsForUser($id)
    {
        $accounts =DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->select('accounts.*', 'account_types.name')
            ->get();
        
        $pagetitle = 'List of accounts';

        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function openedAccounts($id)
    {
            $accounts =DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->whereNull('accounts.deleted_at')
            ->select('accounts.*', 'account_types.name')
            ->get(); 

        $pagetitle = 'List of accounts';
        
        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function closedAccounts($id){

        $accounts =DB::table('accounts')
        ->join('users', 'accounts.owner_id', '=', 'users.id')
        ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
        ->where('users.id', '=', $id)
        ->where('accounts.deleted_at', '!=', 'null')
        ->select('accounts.*', 'account_types.name')
        ->get();

    $pagetitle = 'List of accounts';
    
    if (Auth::check()) {
        return view('accounts.list', compact('accounts', 'pagetitle'));
    }
    return view('errors.user');
    }

    public function destroy($id){
        $accounts = DB::table('accounts')->where('accounts.id', '=', $id)->delete();
        
        return redirect()->action('HomeController@index');
    }

    public function closeAccount($id)
    {
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => date('Y-m-d- G:i:s')]);

        return redirect()->action('HomeController@index');
    }

    public function openAccount($id)
    {
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => NULL]);

        return redirect()->action('HomeController@index');
    }

    public function showMovementsForAccount($id){
        $pagetitle = 'List of Movements';

        $movements = DB::table('movements')
                    ->join('movement_categories', 'movement_categories.id', '=', 'movements.movement_category_id')
                    ->join('accounts', 'movements.account_id', '=', 'accounts.id')
                    ->where('accounts.id', '=', $id)
                    ->select('movements.*', 'accounts.id', 'movement_categories.name')
                    ->get();

        return view('accounts.movements', compact('movements', 'pagetitle'));
 
    }
}
