<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Movement;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountsForUser($id)
    {
        $accounts = DB::table('accounts')
            ->join('users', 'accounts.owner_id', '=', 'users.id')
            ->join('account_types', 'account_types.id', '=', 'accounts.account_type_id')
            ->where('users.id', '=', $id)
            ->select('accounts.*', 'account_types.name')
            ->get();

        $pagetitle = 'List of accounts';

        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle', 'id'));
        }
        return view('errors.user');
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

        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle', 'id'));
        }
        return view('errors.user');
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

        if (Auth::check()) {
            return view('accounts.list', compact('accounts', 'pagetitle', 'id'));
        }
        return view('errors.user');
    }

    public function destroy($id)
    {
        $accounts = DB::table('accounts')->where('accounts.id', '=', $id)->delete();

        return redirect()->route('usersAccount', Auth::user()->id);
    }

    public function closeAccount($id)
    {
        $account = Account::find($id);
        $account->delete();

        return redirect()->route('usersAccount', Auth::user()->id);
    }

    public function openAccount($id)
    {
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => null]);

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

        //dd($movements);

        return view('movements.list', compact('movements', 'pagetitle', 'account'));
    }

    public function createAccount()
    {
        if (Auth::check()) {

            $accountType = DB::table('account_types')
                ->get();

            return view('accounts.create', compact('accountType'));
        }
        return view('errors.user');
    }

    public function storeAccount(AccountRequest $request)
    {
        $request->validated();

        DB::table('accounts')->insert([
            ['owner_id' => Auth::user()->id,
                'account_type_id' => $request->input('account_type_id'),
                'date' => $request->input('date'),
                'code' => $request->input('code'),
                'description' => $request->input('description'),
                'start_balance' => $request->input('start_balance'),
                'current_balance' => $request->input('start_balance')],
        ]);

        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account created successfully');
    }

    public function updateAccountView(Account $account)
    {

        $accountType = DB::table('account_types')
            ->get();

        return view('accounts.update', compact('account', 'accountType'));
    }

    public function updateAccount(Request $request, $id)
    {
        if ($request->has('cancel')) {
            return redirect()->action('HomeController@home');
        }

        $account = $request->validate([
            'account_type_id' => 'required|min:1|max:5',
            'code' => 'required|string|unique:accounts',
            'start_balance' => 'required',
            'description' => 'nullable',
        ]);

        $accountModel = Account::FindOrFail($id);

        $somatorio = DB::table('movements')
            ->join('accounts', 'accounts.id', '=', 'movements.account_id')
            ->where('movements.account_id', '=', $id)
            ->select(DB::raw('sum(movements.value) as somatorioMovimentos'))
            ->get();

        $diferenceValueStartBalance = $request->start_balance - $accountModel->start_balance;

        $accountModel->current_balance = $request->start_balance + $somatorio[0]->somatorioMovimentos;

        $movements = DB::table('movements')->
            where('movements.account_id', '=', $id)->
            select('movements.*')->
            orderBy('movements.date', 'asc')->
            get();

        foreach ($movements as $movement) {
            $mov = Movement::findOrFail($movement->id);

            $start = DB::table('movements')->
                where('movements.account_id', '=', $id)->
                update(['start_balance' => $diferenceValueStartBalance + $mov->start_balance,
                'end_balance' => $mov->end_balance + $diferenceValueStartBalance]);
        }
        dd($mov->end_balance + $diferenceValueStartBalance);

        $accountModel->fill($account);
        $accountModel->save();

        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }
}
