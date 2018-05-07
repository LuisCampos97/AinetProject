<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class WelcomeController extends Controller
{
    public function home()
    {
        $users = User::all();

        $pagetitle = 'Personal Finance Assistant';

        return view('welcome', compact('users', 'pagetitle'));
    }
}
