<?php

namespace App\Http\Controllers;

use App\User;
use Gate;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())){
            return view('users.list', compact('users', 'pagetitle'));
        }
        return 'You are not admin!!!!';
        
    }

    public function block($user)
    {
        if ($user->blocked) {
            return;
        }
        $user->blocked = true;
    }

}
