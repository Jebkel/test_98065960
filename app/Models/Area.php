<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'name',
        'alt_name',
    ];
}
