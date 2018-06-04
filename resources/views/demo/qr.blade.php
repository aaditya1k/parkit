@inject('parkingService', 'App\Services\ParkingService')

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="qr-page">
        <div class="qr-info">
            <h1 class="head">{{ $type }} QR Code</h1>
            <div>{{ $parking->group->name.' - '.$parking->label }}</div>
            <div>{{ ucfirst($parkingService::VEHICLES[ $vehicleType ]) }} Wheeler</div>
            <br/>
            <div class="cf">
                @if ($vehicleType == $parkingService::VEHICLE_TWO)
                    <?php $jsonKey = 'bike'; ?>
                @else
                    <?php $jsonKey = 'car'; ?>
                @endif
                <div class="left">
                @if ($parking->{$jsonKey.'_charge_method'} == $parkingService::CHARGE_METHOD_PER_HOUR)
                    <div class="charge-label">Charge Per Hour &#8377;</div>
                    <div class="charge-input">{{ $parking->{$jsonKey.'_charge_json'}->charge_per_hour }}</div>
                @else
                    <div class="charge-label">Charge &#8377;</div>
                    @foreach($parking->{$jsonKey.'_charge_json'} as $cat)
                        <div class="charge-input">
                            &#8377; {{ $cat->charge }} - {{ $cat->min }}H
                        </div>
                    @endforeach
                @endif
                </div>
                <div class="right">
                    <div class="charge-label">Charge 24 Hour Max.</div>
                    <div class="charge-input">&#8377; {{ $parking->{$jsonKey.'_charge_max'} }}</div>
                </div>
            </div>
        </div>
        <div class="qr-code">
            <img src="{{ asset($image) }}"/>
        </div>
    </div>
</div>
@stop