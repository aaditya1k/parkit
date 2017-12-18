<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class Group extends Model
{
    use FormatTime;

    protected $fillable = [
        'name',
        'api_key'
    ];

    public function parkings()
    {
        return $this->hasMany('\App\Parking');
    }
}
