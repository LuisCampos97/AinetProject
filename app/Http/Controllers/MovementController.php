<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movement;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\MovementRequest;
use App\User;
use Auth;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Account;

class MovementController extends Controller
{
    public function viewCreateMovement($id)
    {
        $accounts = DB::table('accounts')
            ->where('accounts.id', '=', $id)
            ->get();

        $movementType=DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();

        $categories=DB::table('movement_categories')
        ->get();

        return view('movements.create', compact('accounts', 'movementType', 'categories'));
    }

    public function storeMovement(MovementRequest $request, $id)
    {
        $account = Account::FindOrFail($id);

        if($request->input('type') == 'expense'){
            $signal = '-';
        }
        else{
            $signal = '+';
        }

        $request->validated();
       
        $movement = DB::table('movements')->insert([
            'account_id' => $id,
            'movement_category_id' =>$request->input('category'),
            'date' => $request->input('date'),
            'value' =>intval($signal.$request->input('value')),
            'type' =>$request->input('type'),
            'description' => $request->input('description'),
            'start_balance' => $account->current_balance,
            'end_balance' => $account->current_balance +  intval($signal.$request->input('value'))
        ]);

        DB::table('accounts')
        ->where('accounts.id', '=', $id)
        ->update(['current_balance' => $account->current_balance + intval($signal.$request->input('value')),
        'last_movement_date' => date('Y-m-d- G:i:s'),
        ]);
        
        return redirect()->route('movementsForAccount', $id);
    }

    public function deleteMovement($account_id, $movement_id)
    {
        $somatorio=DB::table('movements')
        ->where('account_id', '=', $account_id)
        ->select(DB::raw('sum(movements.value) as somatorioMovimentos'))
        ->get();

        $movements = DB::table('movements')->where('movements.id', '=', $movement_id)->delete();

        DB::table('accounts')->where('id', '=', $account_id)->update(['accounts.current_balance' => $somatorio[0]->somatorioMovimentos + 'accounts.start_balance']);

        return redirect()->action('HomeController@index');
    }
    
}
