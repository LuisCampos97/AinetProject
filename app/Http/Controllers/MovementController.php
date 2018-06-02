<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovementRequest;
use App\Movement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        //dd($categories);

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

        $type = DB::table('movement_categories')
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

    public function deleteMovement($movement_id)
    {
        $movement = Movement::findOrFail($movement_id);
        $account = Account::where('id', '=', $movement->account_id)->first();

        $somatorio = DB::table('movements')
            ->where('account_id', '=', $account->id)
            ->select(DB::raw('sum(movements.value) as somatorioMovimentos'))
            ->get();

            $movementsInAccount = DB::table('movements')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->where('accounts.id', '=', $account_id)
            ->get();
    
            if(count($movementsInAccount) == 0){
                DB::table('accounts')
                ->where('accounts.id', '=', $account_id)
                ->update([
                    'last_movement_date' => NULL
                ]);
            }

        
        //If the movement has a document, delete document too
        if ($movement->document_id != null) {
            $document = Document::where('id', '=', $movement->document_id)->first();

            $unique_id = $movement->id . '.' . $document->type;
            Storage::disk('local')->delete('documents/' . $movement->account_id . '/' . $unique_id);

            $document->delete();
        }
    
        DB::table('accounts')->where('accounts.id', '=', $account->id)->update(['accounts.current_balance' => $somatorio[0]->somatorioMovimentos + intval('accounts.start_balance')]);

        DB::table('movements')->where('movements.id', '=', $movement_id)->delete();


        return redirect()->action('AccountController@showMovementsForAccount', $account->id);
    }

    public function renderViewUpdateMovement($movement_id)
    {
        $movement = Movement::findOrFail($movement_id);
        $account = Account::where('id', '=', $movement->account_id)->first();

        $movementType = DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();

        $categories = DB::table('movement_categories')
            ->get();

        return view('movements.update', compact('account', 'movement', 'movementType', 'categories'));
    }

    public function updateMovement(MovementRequest $request, $movement_id)
    {
        $account = Account::where('id', '=', $movement->account_id)->first();
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
                'end_balance' => $oldStartBalance + $diference,
            ]);

        $diference = $valueMovementInDB - $request->value;

        $valueCurrentBalanceAccountInDB = DB::table('accounts')
            ->where('id', '=', $account_id)
            ->select('current_balance');

        DB::table('accounts')
            ->where('id', '=', $account_id)
            ->update([
                'current_balance' => $valueCurrentBalanceAccountInDB + $diference,
            ]);

        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }

}
