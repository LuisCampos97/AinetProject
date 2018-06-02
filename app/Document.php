<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'original_name', 'description', 'type'
    ];

    const UPDATED_AT = null;

    protected $table = 'documents';
}
