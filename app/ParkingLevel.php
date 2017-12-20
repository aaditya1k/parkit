<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class ParkingLevel extends Model
{
    use FormatTime;

    protected $fillable = [
        'label',
        'parking_id',
        'grid_row',
        'grid_col',
        'grid_map'
    ];

    public function parkingLevelId()
    {
        return 'PL'.$this->id;
    }

    public function scopeGetParking($query, $parkingId)
    {
        return $query->where('parking_id', $parkingId);
    }

    public function parking()
    {
        return $this->belongsTo('\App\Parking');
    }
}
