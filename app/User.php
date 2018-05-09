<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin', 'blocked',
    ];

    public function filter() {

        // Sets the parameters from the get request to the variables.
        $name = Request::get('name');
        $admin = Request::get('admin');
        $blocked = Request::get('blocked');

        // Perform the query using Query Builder
        $result = DB::table('project_db')
            ->select(DB::raw("*"))
            ->where('name', '=', $name)
            ->where('admin', '=', $admin)
            ->where('blocked', '=', $blocked)
            ->get();

        return $result;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function typeToString()
    {
        switch ($this->admin) {
            case 0:
                return 'Normal';
                break;
            case 1:
                return 'Admin';
        }
    }

    public function blockedToString()
    {
        switch ($this->blocked) {
            case 0:
                return 'Unblocked';
                break;
            case 1:
                return 'Blocked';
        }
    }
}
