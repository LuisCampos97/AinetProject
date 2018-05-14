<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account_type_id', 'date', 'description', 'deleted_at',  'start_balance', 'current_balance', 'last_movement_date'
    ];
}
