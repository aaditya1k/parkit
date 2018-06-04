@inject('parkingService', 'App\Services\ParkingService')
@extends('admin.layouts.app')

@section('content')
    <div class="view">
        <div class="label">Parking Id</div>
        <div class="input">{{ $parking->parkingId() }}</div>
    </div>

    <div class="view">
        <div class="label">Group</div>
        <div class="input">{{ $parking->group->name }}</div>
    </div>

    <div class="view">
        <div class="label">Label</div>
        <div class="input">{{ $parking->label }}</div>
    </div>

    <div class="view">
        <div class="label">Random Parking</div>
        <div class="input">{{ $parking->manual_parkno == "1" ? 'Yes' : 'No' }}</div>
    </div>

    <div class="view">
        <div class="label">Secret Key</div>
        <div class="input"><span class="show-value" data-value="{{ $parking->secret_key }}"></span></div>
    </div>

    <div class="view">
        <div class="label">Exit Secret Key</div>
        <div class="input"><span class="show-value" data-value="{{ $parking->exit_generated_key }}"></span></div>
    </div>

    <div class="view">
        <div class="label">Created</div>
        <div class="input">{{ $parking->createdAt() }}</div>
    </div>

    <div class="view">
        <div class="label">Updated</div>
        <div class="input">{{ $parking->updatedAt() }}</div>
    </div>

    <br/>

    <div class="cf">
        <div class="width50 left">
            <div class="view">
                <div class="label">Charge Method (Two Wheeler)</div>
                <div class="input">{{ $parkingService->getChargeMethod($parking->bike_charge_method) }}</div>
            </div>

            @if ($parking->bike_charge_method == $parkingService::CHARGE_METHOD_PER_HOUR)
            <div class="view">
                <div class="label">Charge Per Hour &#8377;</div>
                <div class="input">{{ $parking->bike_charge_json->charge_per_hour }}</div>
            </div>
            @else
            <div class="view">
                <div class="label">Charge Between &#8377;</div>
                @foreach($parking->bike_charge_json as $cat)
                    <div class="input">
                        {{ $cat->min }} hour - &#8377; {{ $cat->charge }}
                    </div>
                @endforeach
            </div>
            @endif

            <div class="view">
                <div class="label">Charge 24hr. Max &#8377;</div>
                <div class="input">{{ $parking->bike_charge_max }}</div>
            </div>

            <div class="view">
                <div class="label">Entry Qr Code</div>
                <div class="input"><img src="{{ asset($parkingService->getQrImage($parking->entry_image, $parkingService::VEHICLE_TWO)) }}"/></div>
            </div>

            <div class="view">
                <div class="label">Exit Qr Code</div>
                <div class="input"><img src="{{ asset($parkingService->getQrImage($parking->exit_image, $parkingService::VEHICLE_TWO)) }}"/></div>
            </div>
        </div>

        <div class="width50 left">
            <div class="view">
                <div class="label">Charge Method (Four Wheeler)</div>
                <div class="input">{{ $parkingService->getChargeMethod($parking->car_charge_method) }}</div>
            </div>

            @if ($parking->car_charge_method == $parkingService::CHARGE_METHOD_PER_HOUR)
            <div class="view">
                <div class="label">Charge Per Hour &#8377;</div>
                <div class="input">{{ $parking->car_charge_json->charge_per_hour }}</div>
            </div>
            @else
            <div class="view">
                <div class="label">Charge Between &#8377;</div>
                @foreach($parking->car_charge_json as $cat)
                    <div class="input">
                        {{ $cat->min }} - &#8377; {{ $cat->charge }}
                    </div>
                @endforeach
            </div>
            @endif

            <div class="view">
                <div class="label">Charge 24hr. Max &#8377;</div>
                <div class="input">{{ $parking->car_charge_max }}</div>
            </div>

            <div class="view">
                <div class="label">Entry Qr Code</div>
                <div class="input"><img src="{{ asset($parkingService->getQrImage($parking->entry_image, $parkingService::VEHICLE_FOUR)) }}"/></div>
            </div>

            <div class="view">
                <div class="label">Exit Qr Code</div>
                <div class="input"><img src="{{ asset($parkingService->getQrImage($parking->exit_image, $parkingService::VEHICLE_FOUR)) }}"/></div>
            </div>
        </div>
    </div>

    <h2 class="head">Levels</h2>
    <div class="options">
        <a href="{{ route('admin:parking-level:new', $parking->id) }}">Create Parking Level</a>
    </div>
    @include('admin.parking-level.index-partial')
@stop