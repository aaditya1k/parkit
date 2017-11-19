<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TfaCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'code',
        'created_at'
    ];

    protected $dates = ['created_at'];
}
