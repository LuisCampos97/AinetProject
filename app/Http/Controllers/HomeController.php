<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function index($id)
    {   

        $user = User::findOrFail($id);
        
        $accounts = DB::table('accounts')
            ->join('users', 'users.id', '=', 'accounts.owner_id')
            ->where('owner_id', '=', $user->id)
            ->get();

        $summary = $accounts->pluck('current_balance');
        $total = $summary->sum();
        $percentage = $accounts->transform(function ($account) use ($total) {
            return number_format($account->current_balance * 100 / $total, 2);
        });

        //$categories = [1,2,3,4,5];
        $movement_catogories = DB::table('movement_categories')
            ->get()
            ->toArray();
     
        foreach ($movement_catogories as $category) {
            $movements = DB::table('accounts')
                ->join('users', 'users.id', '=', 'accounts.owner_id')
                ->join('movements', 'accounts.id', '=', 'movements.account_id')
                ->where('owner_id', '=', $user->id)
                ->where('movements.movement_category_id', '=', $category->id)
                ->get();
            
        }
            
        $summary_by_category = $movements->pluck('value');
        $total_by_category = $summary_by_category->sum();


        $accountsForUser = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->select('accounts.*', 'account_types.name')
            ->get();

        return view('home', ['user' => Auth::user()], compact('total', 'accountsForUser', 'summary', 'percentage', 'movement_catogories', 'total_by_category'))->with('msgglobal', 'Welcome');
    }
}
