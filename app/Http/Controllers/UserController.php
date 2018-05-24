<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\MovementRequest;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Account;

class UserController extends Controller
{
    //Users
    public function index()
    {
        $pagetitle = 'List of users';
        $users = new User;
        $queries = [];
        $i = 0;
        $columns = [
            'type', 'status', 'name',
        ];

        $variables = [
            'admin', 'blocked', 'name',
        ];

        $teste = array(
            array('admin', 1),
            array('normal', 0),
            array('blocked', 1),
            array('unblocked', 0),
            array( request('name'), request('name'))
        );

        if (Gate::allows('admin', auth()->user())) {
            foreach ($columns as $column) {
                if (request()->has($column)) {
                    foreach ($teste as $t) {
                        if (request($column) == $t[0]) {
                            $users = $users->where($variables[$i], 'LIKE', '%' . $t[1] . '%');
                            $queries[$column] = request($column);
                        }
                    }
                }
                $i++;
            }

            //Para poder utilizar vários filtros oa mesmo tempo
            $users = $users->paginate(10)->appends($queries);

            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
    }

    public function block($id)
    {
        if (Gate::allows('admin', auth()->user())) {
            DB::table('users')
                ->where('users.id', '=', $id)
                ->update(['blocked' => 1]);

            return redirect()->action('UserController@index');
        }
        return view('errors.admin');
    }

    public function unblock($id)
    {
        if (Gate::allows('admin', auth()->user())) {
            DB::table('users')
                ->where('users.id', '=', $id)
                ->update(['blocked' => 0]);

            return redirect()->action('UserController@index');
        }
        return view('errors.admin');
    }

    public function promote($id)
    {
        if (Gate::allows('admin', auth()->user())) {
            DB::table('users')
                ->where('users.id', '=', $id)
                ->update(['admin' => 1]);

            return redirect()->action('UserController@index');
        }
        return view('errors.admin');
    }

    public function demote($id)
    {
        if (Gate::allows('admin', auth()->user())) {
            DB::table('users')
                ->where('users.id', '=', $id)
                ->update(['admin' => 0]);

            return redirect()->action('UserController@index');
        }
        return view('errors.admin');
    }

    public function edit(User $user)
    {
        $pagetitle = "Edit user";

        if (Auth::check()) {
            return view('users.edit', compact('pagetitle, user'));
        }
        return view('errors.user');

    }

    //__________________________________________

    //Edit User
    public function update(Request $request) //,$id

    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone' => 'min:3|max:12',
            'profile_photo' => 'mimes:jpeg,png,jpg|max:1999',
        ], [ // Custom Messages
            'name.regex' => 'Name must only contain letters and spaces.',
        ]);

        $userModel = User::findOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'User Edited!']);
    }

    public function editPassword()
    {
        $pagetitle = 'Edit Password';
        if (Auth::check()) {
            return view('users.edit_password', compact('pagetitle'));
        }
        return view('errors.user');
    }

    public function updatePassword(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validate([
            'old_password' => 'required|string|min:6',
            'password' => 'required|min:3|confirmed',
        ]);

        $user['password'] = Hash::make($request->password);

        $userModel = User::FindOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'Password Edited!']);

    }
    
    //__________________________________________

    //Accounts
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

        return redirect()->action('HomeController@index');
    }

    public function closeAccount($id)
    {
        $account = Account::find($id);
        $account->delete();
        /*
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => date('Y-m-d- G:i:s')]);
        */
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

        $account=Account::findOrFail($id);

        
        $pagetitle = 'List of Movements';

        $movements = DB::table('movements')
            ->join('movement_categories', 'movement_categories.id', '=', 'movements.movement_category_id')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->where('accounts.id', '=', $id)
            ->select('movements.*', 'accounts.id', 'movement_categories.name')
            ->get();


        return view('accounts.movements', compact('movements', 'pagetitle', 'account'));
    }

    public function createAccount()
    {
        $accountType = DB::table('account_types')
            ->get();

        return view('accounts.create', compact('accountType'));
    }

    public function storeAccount(AccountRequest $request)
    {
        $request->validated();

        //Account::create($request->all());
        
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
    //__________________________________________

    public function deleteAssociate($id)
    {
        DB::table('associate_members')
            ->where('main_user_id', '=', Auth::user()->id)
            ->where('associated_user_id', '=', $id)
            ->delete();

        return redirect()->action('UserController@associates');
    }

    public function addAssociate()
    {
        $users = User::all();

        $associates = DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();

        return view('associate.add', compact('users', 'associates'));

    }

    public function storeAssociate(Request $request)
    {
        $request->validate([
            'associated_user_id' => 'required',
        ]);

        DB::table('associate_members')->insert([
            ['main_user_id' => Auth::user()->id,
                'associated_user_id' => $request->input('associated_user_id')],
        ]);

        return redirect()->route('associates')
            ->with('success', 'Associate added successfully');
    }

    public function updateAccountView($account)
    {
        $accountType = DB::table('account_types')
            ->get();
        $account = DB::table('accounts')
            ->get();

        return view('accounts.update', compact('account', 'accountType'));
    }

    public function updateAccount(AccountRequest $request)
    {
        if ($request->has('cancel')) {
            return redirect()->action('HomeController@home');
        }

        $account = $request->validated();

        $accountModel = Account::FindOrFail($account->id);
        $accountModel->fill($account);
        $accountModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'Password Edited!']);
    }

    public function viewCreateMovement($id)
    {
        $accounts = DB::table('accounts')
            ->where('accounts.id', '=', $id)
            ->get();
        //dd($accounts[0]);
        $movementType=DB::table('movements')
            ->select('movements.type')
            ->distinct()
            ->get();
        //dd($movementType);

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
        /*
        DB::table('movements')
        ->join('accounts', $account_id, '=', 'movements.account_id')
        ->where('movements.id', '=', $movement_id)
        ->delete();
        */

        //dd($movement_id);
        //dd($account_id);

        $movements = DB::table('movements')->where('movements.id', '=', $movement_id)->delete();

        return redirect()->action('HomeController@index');
    }
}
