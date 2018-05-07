<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pagetitle = 'List of users';
        
        return view('users.list', compact('users', 'pagetitle'));
    }

    public function home()
    {
        $users = User::all();
        $pagetitle = 'Personal Finance Assistant';

        return view('welcome', compact('users', 'pagetitle'));
    }

    public function block($user) {
        if($user->blocked) 
        {
            return;
        }
        $user->blocked = true;
    }
}
