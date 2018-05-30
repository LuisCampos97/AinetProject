<?php

namespace App\Providers;

use App\User;
use App\Account;
use App\Movement;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Gate to define an admin
         */
        Gate::define('admin', function ($user) {
            return $user->admin == 1;
        });

        /**
         * Gate to define that only the account owner can change it.
         */
        Gate::define('change-account', function ($user, $account_id) {

            //If the parameter is a Account id
            if (is_numeric($account_id)) {
                $account = Account::withTrashed()
                    ->where('id', '=', $account_id)
                    ->first();

                if(is_null($account)) {
                    return response('User not found', 404);
                }

                return $user->id == $account->owner_id;
            }

            //If the parameter is a Account (Just the updateAccountView method)
            $account = $account_id;
            return $user->id == $account->owner_id;
        });

        /**
         * Gate to define that only me and my associates
         * can have access to my personal finances just to read.
         */
        Gate::define('associate', function ($user, $associate) {
            //Fails if associate id dont't exist
            User::findOrFail($associate);

            $users = DB::table('users')
                ->leftJoin('associate_members', 'users.id', '=', 'associate_members.main_user_id')
                ->where('associate_members.associated_user_id', $user->id)
                ->orWhere('users.id', '=', $user->id)
                ->get();

            return $users->where('id', $associate)->isNotEmpty();
        });

        /**
         * Gate to define that only the account owner can change the movement.
         */
        Gate::define('change-movement', function ($user, $movement_id) {
            $movement = Movement::findOrFail($movement_id);
            $account = Account::findOrFail($movement->account_id);

            return $user->id == $account->owner_id;
        });

        /**
         * Gate to define that only me and my associates
         * can have access to my movements just to read.
         */
        Gate::define('view-movements', function($user, $account_id) {
            $account = Account::findOrFail($account_id);

            $users = DB::table('users')
                ->leftJoin('associate_members', 'users.id', '=', 'main_user_id')
                ->where('associate_members.associated_user_id', $user->id)
                ->orWhere('users.id', '=', $user->id)
                ->get();

            return $users->where('id', $account->owner_id)->isNotEmpty();   

        });

        Gate::define('owner', function($user1, $user2) {
            User::findOrFail($user2);
            return $user1->id == $user2;
        });
    }
}
