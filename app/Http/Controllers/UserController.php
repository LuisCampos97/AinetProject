<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pagetitle = 'List of users';

        return view('users.list', compact('users', 'pagetitle'));
    }

    public function block($user) {
        if($user->blocked) 
        {
            return;
        }
        $user->blocked = true;
    }

    
}
