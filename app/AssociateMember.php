<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssociateMember extends Model
{
    protected $fillable = [
        'main_user_id', 'associated_user_id'
    ];

    const UPDATED_AT = null;
}
