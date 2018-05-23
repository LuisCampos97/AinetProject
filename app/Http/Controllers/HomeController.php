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
                ->select(DB::raw('SUM(accounts.current_balance) as somatorio'))
                ->get();
//dd($total[0]->somatorio);
        $accountsForUser = DB::table('accounts')
                ->join('users', 'accounts.owner_id', '=', 'users.id')
                ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
                ->where('users.id', '=', Auth::user()->id)
                ->select('accounts.*', 'account_types.name')
                ->get();
        //dd($accountsForUser);

       
        return view('home', compact('total', 'accountsForUser'))->with('msgglobal', 'Welcome');
    }
}
