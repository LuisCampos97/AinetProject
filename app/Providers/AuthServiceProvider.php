<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//use DB;
//use Auth;

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

        Gate::define('admin', function ($user) {
            if($user->admin == 1)
            {
                return true;
            }
            return false;
        });

        /*Gate::define('associate', function($associate) {
            $associates = DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', Auth::user()->id)
            ->get();

            return $associates->where('id', $associate->id)->isNotEmpty();
        });*/
    }
}
