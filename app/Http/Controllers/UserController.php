<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
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
        return view('users.edit', compact('pagetitle, user'));
    }

    public function update(Request $request) //,$id
    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' =>  'required|email|unique:users,email,'.Auth::user()->id,
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

        return view('users.edit_password', compact('pagetitle, user'));
    }

    public function updatePassword(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'password' => 'required|min:3|confirmed'
        ]);

        $user['password'] = Hash::make($request->password);

        $userModel=User::FindOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'Password Edited!']);

    }
}
