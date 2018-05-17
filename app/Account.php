<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'code', 'account_type_id', 'date', 'description', 'start_balance', 'updated_at', 'created_at'
    ];
}
