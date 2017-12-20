<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class Parking extends Model
{
    use FormatTime;

    protected $fillable = [
        'parking_id',
        'group_id',
        'position',
        'vehicle_type',
        'exit_charges',
        'exited_at'
    ];

    public function group()
    {
        return $this->belongsTo('\App\Group');
    }

    public function parking()
    {
        return $this->belongsTo('\App\Parking');
    }
}
