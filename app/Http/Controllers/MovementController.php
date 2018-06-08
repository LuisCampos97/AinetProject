<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovementRequest;
use App\Movement;
use App\MovementCategory;
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

        $document = Movement::where('document_id', '=', $id);

        $categories = DB::table('movement_categories')
            ->get();

        return view('movements.create', compact('account', 'movementType', 'categories', 'document'));
    }

    public function storeMovement(MovementRequest $request, $id)
    {
        $account = Account::findOrFail($id);

        $request->validated();

        $file = $request->file('document_file');

        $document = new Document;

        if ($file != null) {
            $document = Document::create([
                'original_name' => $file->getClientOriginalName(),
                'description' => $request->input('document_description'),
                'type' => $file->getClientOriginalExtension(),
            ]);
        }

        $movementCategory = MovementCategory::findOrFail($request->input('movement_category_id'));

        if ($movementCategory->type == 'expense') {
            $signal = '-';
        } else {
            $signal = '+';
        }

        $movement = Movement::create([
            'account_id' => $id,
            'movement_category_id' => $movementCategory->id,
            'date' => $request->input('date'),
            'value' => $request->input('value'),
            'type' => $movementCategory->type,
            'document_id' => $document->id,
            'description' => $request->input('description'),
            'start_balance' => $account->current_balance,
            'end_balance' => $account->current_balance + floatval($signal . $request->input('value')),
        ]);

        if ($file != null) {
            if ($file->isValid()) {
                $name = $movement->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
            }
        }

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
            ->where('accounts.id', '=', $account->id)
            ->get();

        if (count($movementsInAccount) == 0) {
            DB::table('accounts')
                ->where('accounts.id', '=', $account->id)
                ->update([
                    'last_movement_date' => null,
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
        $movement = Movement::findOrFail($movement_id);
        $account = Account::where('id', '=', $movement->account_id)->first();
        $movementInput = $request->validated();

        $file = $request->file('document_file');

        //If the movement dont has a document
        if ($file != null && $movement->document_id == null) {
            $document = Document::create([
                'original_name' => $file->getClientOriginalName(),
                'description' => $request->input('document_description'),
                'type' => $file->getClientOriginalExtension(),
            ]);

            $movement->document_id = $document->id;

            if ($file->isValid()) {
                $name = $movement->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
            }

        } else {
            //If the movement has a document and the user wants to change it.
            if ($file != null) {
                $document = Document::findOrFail($movement->document_id);

                $unique_id = $movement->id . '.' . $document->type;
                Storage::disk('local')->delete('documents/' . $movement->account_id . '/' . $unique_id);

                $document->original_name = $file->getClientOriginalName();
                $document->description = $request->input('document_description');
                $document->type = $file->getClientOriginalExtension();
                $document->save();

                if ($file->isValid()) {
                    $name = $movement->id . '.' . $file->getClientOriginalExtension();
                    Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
                }
            }
        }

        $movementCategory = MovementCategory::findOrFail($request->input('movement_category_id'));

        if ($movementCategory->type == 'expense') {
            $signal = '-';
        } else {
            $signal = '+';
        }

        $movement->fill($movementInput);
        $movement->movement_category_id = $movementCategory->id;
        $movement->type = $movementCategory->type;
        $movement->end_balance = $movement->start_balance + floatval($signal . $request->input('value'));
        $movement->save();

        return redirect()->route('movementsForAccount', $movement->account_id);
    }

}
