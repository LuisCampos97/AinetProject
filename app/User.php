<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin', 'blocked', 'phone', 'profile_photo',
    ];

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

    public function associates_of()
    {
        return DB::table('users')
            ->join('associate_members', 'users.id', '=', 'main_user_id')
            ->where('associate_members.associated_user_id', $this->id)
            ->get();
    }

    public function my_associates()
    {
        return DB::table('users')
            ->join('associate_members', 'associated_user_id', '=', 'users.id')
            ->where('associate_members.main_user_id', $this->id)
            ->get();
    }
}
