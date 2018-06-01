<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovementRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewCreateMovement($id)
    {
        $account = Account::findOrFail($id);
        
        $movementType = DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();
        

        $categories = DB::table('movement_categories')
            ->get();

        return view('movements.create', compact('account', 'movementType', 'categories'));
    }

    public function storeMovement(MovementRequest $request, $id)
    {
        //dd($request);
        $account = Account::findOrFail($id);

        if ($request->input('type') == 'expense') {
            $signal = '-';
        } else {
            $signal = '+';
        }

        $movement = $request->validated();

        $type=DB::table('movement_categories')
        ->where('movement_categories.id', '=', $request->movement_category_id)
        ->select('movement_categories.type')
        ->first();

        if ($type->type == 'expense') {
            $signal = '-';
        } else {
            $signal = '+';
        }
        
        DB::table('movements')->insert([
            'account_id' => $id,
            'movement_category_id' => $request->input('movement_category_id'),
            'date' => $request->input('date'),
            'value' => intval($signal . $request->input('value')),
            'type' => $type->type,
            'description' => $request->input('description'),
            'start_balance' => $account->current_balance,
            'end_balance' => $account->current_balance + intval($signal . $request->input('value')),
        ]);

        

        DB::table('accounts')
            ->where('accounts.id', '=', $id)
            ->update(['current_balance' => $account->current_balance + intval($signal . $request->input('value')),
                'last_movement_date' => date('Y-m-d- G:i:s'),
        ]);

        return redirect()->route('movementsForAccount', $id);
    }

    public function deleteMovement($account_id, $movement_id)
    {
        $somatorio = DB::table('movements')
            ->where('account_id', '=', $account_id)
            ->select(DB::raw('sum(movements.value) as somatorioMovimentos'))
            ->get();

        $movements = DB::table('movements')->where('movements.id', '=', $movement_id)->delete();

        DB::table('accounts')->where('accounts.id', '=', $account_id)->update(['accounts.current_balance' => $somatorio[0]->somatorioMovimentos + intval('accounts.start_balance')]);

        return redirect()->action('AccountController@showMovementsForAccount', $account_id);
    }

    public function renderViewUpdateMovement(Account $account, Movement $movement)
    {
        $movementType = DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();

        $categories = DB::table('movement_categories')
            ->get();

        return view('movements.update', compact('account', 'movement', 'movementType', 'categories'));
    }

    public function updateMovement(MovementRequest $request, $movement_id, $account_id)
    {
        $account=Account::findOrFail($account_id);
        $movementModel = Movement::FindOrFail($movement_id);
        $movement = $request->validated();

        $valueMovementInDB = DB::table('movements')
        ->where('id', '=', $movement_id)
        ->select('value')
        ->get();

        $oldStartBalance = DB::table('movements')
        ->where('id', '=', $movement_id)
        ->select('start_balance')
        ->get();

        $test = DB::table('movements')
        ->where('account_id', '=', $account_id)
        ->update([
            'movement_category_id' => $request->input('category'),
            'value' => intval($signal . $request->input('value')),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'end_balance' => $oldStartBalance + $diference
        ]);

        $diference= $valueMovementInDB -$request->value;

        $valueCurrentBalanceAccountInDB = DB::table('accounts')
                            ->where('id', '=', $account_id)
                            ->select('current_balance');

        DB::table('accounts')
        ->where('id', '=', $account_id)
        ->update([
        'current_balance' => $valueCurrentBalanceAccountInDB + $diference
        ]);
        
        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }

}
