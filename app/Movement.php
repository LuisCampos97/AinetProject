<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'account_id', 'type', 'movement_category_id', 'date', 'value', 'start_balance', 
        'end_balance', 'document_id', 'description'
    ];

    const UPDATED_AT = null;
}
