<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidAssociate;
use App\AssociateMember;
use App\Http\Requests\AssociateRequest;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profiles()
    {
        $query = request('name');
        $users = User::where('name', 'LIKE', '%' . $query . '%');

        $associatesOf = Auth::user()->associates_of();

        $associates = Auth::user()->my_associates();

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
        $users = Auth::user()->associates_of();

        $pagetitle = 'List of Associated-of profiles';

        if (Auth::check()) {
            return view('users.associateof', compact('users', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function associates()
    {
        $users = Auth::user()->my_associates();

        $pagetitle = 'List of associates';

        if (Auth::check()) {
            return view('users.associates', compact('users', 'pagetitle'));
        }
        return view('errors.user');
    }

    public function deleteAssociate($id)
    {
        $user = User::findOrFail($id);

        $associates = Auth::user()->my_associates()->toArray();

        if (!in_array($id, array_column($associates, 'id'))) {
            return response('User not found', 404);
        }

        DB::table('associate_members')
            ->where('main_user_id', '=', Auth::user()->id)
            ->where('associated_user_id', '=', $id)
            ->delete();

        return redirect()->action('ProfileController@associates');
    }

    public function addAssociate()
    {
        $users = User::all();

        $associates = Auth::user()->my_associates();

        return view('associate.add', compact('users', 'associates'));
    }

    public function storeAssociate(AssociateRequest $request)
    {   
        $request->validated();

        $associated_user = $request->input('associated_user');

        AssociateMember::create([
            'main_user_id' => Auth::user()->id,
            'associated_user_id' => $associated_user
        ]);

        return redirect()->route('associates')
            ->with('success', 'Associate added successfully');
    }

}
