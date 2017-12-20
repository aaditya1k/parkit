<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class Activity extends Model
{
    use FormatTime;

    protected $fillable = [
        'type',
        'user_id',
        'data1',
        'data2',
        'created_at'
    ];

    public $timestamps = false;
}
