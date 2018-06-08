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

        $previousMovement = Movement::where('date', '<=', $request->input('date'))
            ->where('account_id', $account->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($previousMovement == null) {
            $end_balance = $account->start_balance;
        } else {
            $end_balance = $previousMovement->end_balance;
        }

        $movement = Movement::create([
            'account_id' => $id,
            'movement_category_id' => $movementCategory->id,
            'date' => $request->input('date'),
            'value' => $request->input('value'),
            'type' => $movementCategory->type,
            'document_id' => $document->id,
            'description' => $request->input('description'),
            'start_balance' =>  $end_balance,
            'end_balance' =>  $end_balance + floatval($signal . $request->input('value')),
        ]);

        $posteriorMovements = Movement::where('date', '>', $movement->date)
            ->where('account_id', $account->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($posteriorMovements as $m) {
            if ($m->type == 'expense') {
                $s = '-';
            } else {
                $s = '+';
            }

            $m->start_balance += floatval($signal . $request->input('value'));
            $m->end_balance = $m->start_balance + floatval($s . $m->value);
            $m->save();
        }

        if ($file != null) {
            if ($file->isValid()) {
                $name = $movement->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
            }
        }

        $moreRecentMovement = Movement::where('account_id', $account->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $account->current_balance = $moreRecentMovement->end_balance;
        $account->last_movement_date = $moreRecentMovement->date;
        $account->save();

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

        if (count($movementsInAccount) == 0 && $movement->document_id != null) {
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
