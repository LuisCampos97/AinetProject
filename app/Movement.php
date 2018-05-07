<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'type', 'category', 'date', 'value', 'start_balance', 'end_balance'
    ];


}
