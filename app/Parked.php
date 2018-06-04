<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class Parked extends Model
{
    use FormatTime;

    protected $table = "parked";

    protected $fillable = [
        'parking_id',
        'group_id',
        'parking_level_id',
        'user_id',
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
