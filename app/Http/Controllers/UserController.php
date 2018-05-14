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
        $users = User::all();
        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');

    }

    public function block($id)
    {
        if ($user->blocked) {
            return;
        }
        $user->blocked = true;
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

    public function profiles()
    {
        $users = User::all();
        $pagetitle = 'List of profiles';

        return view('users.profiles', compact('users', 'pagetitle'));
    }

    public function accountsForUser($id)
    {
        $accounts = DB::table('accounts')->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->get();
        $pagetitle = 'List of accounts';

        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function openedAccounts($id)
    {
        $accounts = DB::table('accounts')->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->whereNull('accounts.deleted_at')
            ->get();
        $pagetitle = 'List of accounts';
        
        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function closedAccounts($id){
        $accounts = DB::table('accounts')->join('users', 'accounts.owner_id', '=', 'users.id')
        ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
        ->where('users.id', '=', $id)
        ->where('accounts.deleted_at', '!=', 'null')
        ->get();
    $pagetitle = 'List of accounts';
    
    if (Auth::check()) {
        return view('accounts.list', compact('accounts', 'pagetitle'));
    }
    return view('errors.user');
    }
}
