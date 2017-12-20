<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatTime;

class Parking extends Model
{
    use FormatTime;

    protected $fillable = [
        'group_id',
        'label',
        'secret_key',
        'exit_generated_key',
        'manual_parkno',
        'entry_image',
        'exit_image',
        'bike_charge_method',
        'bike_charge_json',
        'bike_charge_max',
        'car_charge_method',
        'car_charge_json',
        'car_charge_max',
    ];

    protected $hidden = [
        'secret_key',
        'exit_generated_key'
    ];

    public function parkingId()
    {
        return 'P'.$this->id;
    }

    public function group()
    {
        return $this->belongsTo('\App\Group');
    }

    public function getBikeChargeJsonAttribute($value)
    {
        return json_decode($value);
    }

    public function getCarChargeJsonAttribute($value)
    {
        return json_decode($value);
    }

    public function parkingLevels()
    {
        return $this->hasMany('\App\ParkingLevel');
    }
}
