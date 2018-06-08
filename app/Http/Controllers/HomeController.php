<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

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

    public function index($id, Request $dates)
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


        //US.27
        $movement_categories = DB::table('movement_categories')
            ->get()
            ->toArray();

        $total_by_category = array();

        $date1=new DateTime($dates->input('data1'));
        $date2=new DateTime($dates->input('data2'));
        $strip1 = $date1->format('Y-m-d');
        $strip2 = $date2->format('Y-m-d');
        
        $i = 0;
        foreach ($movement_categories as $category) {
            $movements = DB::table('accounts')
                ->join('users', 'users.id', '=', 'accounts.owner_id')
                ->join('movements', 'accounts.id', '=', 'movements.account_id')
                ->where('owner_id', '=', $id)
                ->where('movements.movement_category_id', '=', $category->id)
                ->where('movements.date', '>=', $strip1 )
                ->where('movements.date', '<=', $strip2 )
                ->get();

            $summary_by_category = $movements->pluck('value');
            $total_by_category[$i] = $summary_by_category->sum();
            $i++;
        }

        $accountsForUser = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->select('accounts.*', 'account_types.name')
            ->get();

        return view('home', ['user' => Auth::user()], compact('total', 'accountsForUser', 'summary', 'percentage', 'movement_categories', 'total_by_category'))->with('msgglobal', 'Welcome');
    }
}
