<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
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
        $account = $request->validated();

        if (!$request->filled('date')) {
            $request->date = new Carbon();
        }

        $codes = DB::table('accounts')
            ->select('code')
            ->get();

        $users=User::all();
        
        foreach($codes as $code){
            if($code===$request->input('code')){
                return Redirect::back()->withErrors(['errors', 'Code already exists']);
            }
        }

        $accountTypes = DB::table('account_types')
                                ->get();

        $numberOfAccountTypes = count($accountTypes);

        if(intval($request->input('account_type_id')) > $numberOfAccountTypes){
            return Redirect::back()->withErrors(['errors', 'Error']);
       }

        DB::table('accounts')->insert([
            ['owner_id' => Auth::user()->id,
                'account_type_id' => $request->input('account_type_id'),
                'date' => $request->date,
                'code' => $request->input('code'),
                'description' => $request->input('description'),
                'start_balance' => $request->input('start_balance'),
                'current_balance' => $request->input('start_balance')],
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
        $accountModel = Account::FindOrFail($id);

        $account = $request->validate([
            'account_type_id' => 'required|min:1|max:5',
            'date' => 'required|date',
            'code' => 'required|string|unique:accounts',
            'description' => 'nullable|string',
            'start_balance' => 'required|numeric',
        ]);

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

        $accountModel->code = $request->input('code');
        $accountModel->fill($account);
        $accountModel->save();

        
        return redirect()->route('usersAccount', Auth::user()->id)
            ->with('msgglobal', 'Account edited successfully');
    }
}
