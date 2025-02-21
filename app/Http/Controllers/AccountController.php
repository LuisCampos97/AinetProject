<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Movement;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountsForUser($id)
    {
        User::findOrFail($id);

        $accounts = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->select('accounts.*', 'account_types.name')
            ->get();

        $pagetitle = 'List of accounts';

        return view('accounts.list', compact('accounts', 'pagetitle', 'id'));

    }

    public function openedAccounts($id)
    {
        $accounts = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->whereNull('accounts.deleted_at')
            ->select('accounts.*', 'account_types.name')
            ->get();

        $pagetitle = 'List of accounts';

        return view('accounts.list', compact('accounts', 'pagetitle', 'id'));
    }

    public function closedAccounts($id)
    {
        $accounts = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->where('accounts.deleted_at', '!=', 'null')
            ->select('accounts.*', 'account_types.name')
            ->get();

        $pagetitle = 'List of accounts';

        return view('accounts.list', compact('accounts', 'pagetitle', 'id'));

    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        $movements = DB::table('movements')
            ->join('accounts', 'accounts.id', '=', 'movements.account_id')
            ->where('account_id', '=', $id)
            ->get();

        if (is_null($account->last_movement_date) && count($movements) == 0) {
            $account->forceDelete();

            return redirect()->route('usersAccount', Auth::user()->id);
        }
    }

    public function closeAccount($id)
    {
        $account = Account::findOrFail($id);

        $account->delete();

        return redirect()->route('usersAccount', Auth::user()->id);
    }

    public function openAccount($id)
    {
        $account = Account::withTrashed()->findOrFail($id);

        $account->deleted_at = null;
        $account->save();

        return redirect()->route('usersAccount', Auth::user()->id);
    }

    public function showMovementsForAccount($id)
    {
        $account = Account::findOrFail($id);

        $pagetitle = 'List of Movements';

        $movements = DB::table('movements')
            ->join('movement_categories', 'movement_categories.id', '=', 'movements.movement_category_id')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->leftJoin('documents', 'documents.id', '=', 'movements.document_id')
            ->where('movements.account_id', '=', $id)
            ->select('movements.*', 'movement_categories.name', 'documents.original_name')
            ->orderBy('movements.date', 'desc')
            ->orderBy('movements.id', 'desc')
            ->get();

        return view('movements.list', compact('movements', 'pagetitle', 'account'));
    }

    public function createAccount()
    {
        $accountType = DB::table('account_types')
            ->get();

        return view('accounts.create', compact('accountType'));
    }

    public function storeAccount(AccountRequest $request)
    {
        $account = $request->validated();

        if (!$request->filled('date')) {
            $request->date = Carbon::now();
        }

        $account = Account::create([
            'owner_id' => Auth::user()->id,
            'account_type_id' => $request->input('account_type_id'),
            'date' => $request->date,
            'code' => $request->input('code'),
            'description' => $request->input('description'),
            'start_balance' => $request->input('start_balance'),
            'current_balance' => $request->input('start_balance'),
            'email' => $request->input('email')
        ]);

        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account created successfully');
    }

    public function updateAccountView($id)
    {
        $account = Account::findOrFail($id);

        $accountType = DB::table('account_types')
            ->get();

        return view('accounts.update', compact('account', 'accountType'));
    }
    public function updateAccount(Request $request, $id)
    {
        $accountModel = Account::findOrFail($id);

        $account = $request->validate([
            'account_type_id' => 'required|min:1|max:5',
            'date' => 'required|date',
            'code' => 'required|string|unique:accounts,code,'.$id,
            'description' => 'nullable|string',
            'start_balance' => 'required|numeric',
        ]);

        $diferenceOfValues = $request->start_balance - $accountModel->start_balance;

        //dd($movementsInThisAccount);

        $accountModel->start_balance += $diferenceOfValues;
        $accountModel->current_balance += $diferenceOfValues;
        $accountModel->code = $request->input('code');

        $movementsInThisAccount=Movement::where('movements.account_id', '=',  $id)->get();

        if(count($movementsInThisAccount) > 0) {
            foreach($movementsInThisAccount as $movement){
                $movement->start_balance+=$diferenceOfValues;
                $movement->end_balance += $diferenceOfValues;
                $movement->save();
            }
        }

        $accountModel->fill($account);
        $accountModel->save();
    
        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }
}
