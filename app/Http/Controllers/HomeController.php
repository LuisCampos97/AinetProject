<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

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
    public function index($user)
    {
        if (Auth::check()) {
            $total = DB::table('accounts')
                ->join('users', 'users.id', '=', 'accounts.owner_id')
                ->where('owner_id', '=', $user)
                ->select(DB::raw('SUM(accounts.current_balance) as somatorio'))
                ->get();

            $accountsForUser = DB::table('accounts')
                ->join('users', 'accounts.owner_id', '=', 'users.id')
                ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
                ->where('users.id', '=', $user)
                ->select('accounts.*', 'account_types.name')
                ->get();

            return view('home',['user'=>Auth::user()->id], compact('total', 'accountsForUser'))->with('msgglobal', 'Welcome');
        }
        return response('Unauthorized action.', 404);
    }
}
