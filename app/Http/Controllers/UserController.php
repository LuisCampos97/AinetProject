<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Users
    public function search(Request $request)
    {
        $query = $request->search;
        $users = User::where('name', 'LIKE', '%' . $query . '%')->paginate(10);

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
    }

    public function index()
    {
        $pagetitle = 'List of users';
        $users = new User;
        $queries = [];
        $i = 0;
        $columns = [
            'type', 'status', 'name'
        ];

        $variables = [
            'admin', 'blocked', 'name'
        ];


        if (Gate::allows('admin', auth()->user())) {
            foreach ($columns as $column) {
                if(request()->has($column)) {
                    $users = $users->where($variables[$i], 'LIKE', '%' . request($column) . '%');
                    $queries[$column] = request($column);
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

    public function normalUser()
    {
        $users = User::where('admin', '=', '0')->paginate(10);

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
    }

    public function adminUser()
    {
        $users = User::where('admin', '=', '1')->paginate(10);

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
    }

    public function unblockedUser()
    {
        $users = User::where('blocked', '=', '0')->paginate(10);

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
    }

    public function blockedUser()
    {
        $users = User::where('blocked', '=', '1')->paginate(10);

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.list', compact('users', 'pagetitle'));
        }
        return view('errors.admin');
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
            'profile' => 'mimes:jpeg,png,jpg|max:1024',
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
            'password' => 'required|min:3|confirmed',
        ]);

        $user['password'] = Hash::make($request->password);

        $userModel = User::FindOrFail(Auth::user()->id);
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('HomeController@index')->with(['msgglobal' => 'Password Edited!']);

    }
    //__________________________________________

    //Profiles
    public function profiles(Request $request)
    {
        $users = User::all();

        $associatesOf = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'main_user_id')
            ->where('associate_members.associated_user_id', Auth::user()->id)
            ->get();

        $associates = DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of profiles';

        if (Auth::check()) {
            return view('users.profiles', compact('users', 'associates', 'associatesOf', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function associateOf()
    {
        $users = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'main_user_id')
            ->where('associate_members.associated_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of Associated-of profiles';

        if (Auth::check()) {
            return view('users.associateof', compact('users', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function associates()
    {
        $users = DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of associates';

        if (Auth::check()) {
            return view('users.associates', compact('users', 'pagetitle'));
        }
        return view('errors.user');
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
            return view('accounts.list', compact('accounts', 'pagetitle'));
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
            return view('accounts.list', compact('accounts', 'pagetitle'));
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
            return view('accounts.list', compact('accounts', 'pagetitle'));
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
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => date('Y-m-d- G:i:s')]);

        return redirect()->action('HomeController@index');
    }

    public function openAccount($id)
    {
        DB::table('accounts')
            ->where('accounts.id', $id)
            ->update(['deleted_at' => null]);

        return redirect()->action('HomeController@index');
    }

    public function showMovementsForAccount($id)
    {
        $pagetitle = 'List of Movements';

        $movements = DB::table('movements')
            ->join('movement_categories', 'movement_categories.id', '=', 'movements.movement_category_id')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->where('accounts.id', '=', $id)
            ->select('movements.*', 'accounts.id', 'movement_categories.name')
            ->get();

        return view('accounts.movements', compact('movements', 'pagetitle'));
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

        return redirect()->route('home')
            ->with('success', 'Account created successfully');
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
}
