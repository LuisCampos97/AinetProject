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
        'name', 'email', 'password', 'admin', 'blocked','id'
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
}
