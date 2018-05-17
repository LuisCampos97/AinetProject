<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'owner_id', 'code', 'account_type_id', 'date', 'description', 'start_balance', 'created_at'
    ];

    public $timestamp = false;
}
