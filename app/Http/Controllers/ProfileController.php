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
use App\Account;

class ProfileController extends Controller
{
    public function profiles(Request $request)
    {
        $query = $request->search;
        $users = User::where('name', 'LIKE', '%' . $query . '%');

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
            $users = $users->paginate(10);
            return view('users.profiles', compact('users', 'associates', 'associatesOf', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function searchNameProfiles(Request $request)
    {
        $query = $request->search;
        $users = User::where('name', 'LIKE', '%' . $query . '%')->paginate(10);
        $associates = DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();
        $associatesOf = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'main_user_id')
            ->where('associate_members.associated_user_id', Auth::user()->id)
            ->get();

        $pagetitle = 'List of users';

        if (Gate::allows('admin', auth()->user())) {
            return view('users.profiles', compact('users', 'associates', 'associatesOf', 'pagetitle'));
        }
        return view('errors.admin');
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
}
