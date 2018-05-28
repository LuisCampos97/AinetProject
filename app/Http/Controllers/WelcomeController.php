<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use App\User;

class WelcomeController extends Controller
{
    public function home()
    {
        $users = User::all();
        $accounts = Account::all();
        $movements = Movement::all();

        $pagetitle = 'Personal Finance Assistant';

        return view('welcome', compact('users', 'accounts', 'movements', 'pagetitle'));
    }
}
