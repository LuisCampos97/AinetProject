<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total = DB::table('accounts')
                ->join('users', 'users.id', '=', 'accounts.owner_id')
                ->where('owner_id', '=', Auth::user()->id)
                ->select(DB::raw('SUM(accounts.current_balance)'))
                ->get();

        return view('home', compact('total'));
    }
}
