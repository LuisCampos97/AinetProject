<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
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

    public function update(UserRequest $request) //,$id

    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }

        $user = $request->validated();

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
}
