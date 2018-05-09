<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'type', 'movement_category_id', 'date', 'value', 'start_balance', 'end_balance'
    ];
}
