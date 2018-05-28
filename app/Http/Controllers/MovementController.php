<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovementRequest;
use Illuminate\Support\Facades\DB;
use App\Movement;

class MovementController extends Controller
{
    public function viewCreateMovement(Account $account)
    {
        $movementType=DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();

        $categories = DB::table('movement_categories')
            ->get();

        return view('movements.create', compact('account', 'movementType', 'categories'));
    }

    public function storeMovement(MovementRequest $request, $id)
    {
        $account = Account::FindOrFail($id);

        if ($request->input('type') == 'expense') {
            $signal = '-';
        } else {
            $signal = '+';
        }

        $request->validated();

        $movement = DB::table('movements')->insert([
            'account_id' => $id,
            'movement_category_id' => $request->input('category'),
            'date' => $request->input('date'),
            'value' => intval($signal . $request->input('value')),
            'type' => $request->input('type'),
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
        $movementType=DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();

        $categories=DB::table('movement_categories')
            ->get();

        return view('movements.update', compact('account', 'movement', 'movementType', 'categories'));
    }

    public function updateMovement(MovementRequest $request, $movement_id, $account_id)
    {
        if ($request->has('cancel')) {
            return redirect()->action('HomeController@home');
        }

        $movement = $request->validated([
            'type' =>'required|min:1',
            'category' =>'required|min:1',
            'date' => 'required|date',
            'value' => 'required',
            'description' => 'nullable'
        ]);

        $movementModel = Movement::FindOrFail($movement_id);
        //dd($movementModel);
        $movementModel->fill($movement);
        $movementModel->save();

        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }
    
}
