<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id', 'code', 'account_type_id', 'date', 'description', 'start_balance', 'created_at, last_movement_date', 'current_balance'
    ];

    protected $dates = ['deleted_at'];

    const UPDATED_AT = null;
}
