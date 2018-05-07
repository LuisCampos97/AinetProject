<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\Movement;

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
