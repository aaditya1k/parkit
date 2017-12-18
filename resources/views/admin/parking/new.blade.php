@inject('groupService', 'App\Services\GroupService')
@inject('parkingService', 'App\Services\ParkingService')
@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.status')

    <h2 class="head">New</h2>

    {!! Form::open(['url' => route('admin:parking:create'), 'id' => 'admin-parking-form']) !!}
        <div class="form">
            <div class="label">Group</div>
            <div class="input">
                {!! Form::select('group_id', $groupService->getAll(), old('group_id')) !!}
            </div>
        </div>

        <div class="form">
            <div class="label">Label</div>
            <div class="input">
                {!! Form::text('label', old('label')) !!}
            </div>
        </div>

        <div class="form">
            <div class="label">Manual Parking numbers</div>
            <div class="input">
                {!! Form::checkbox('manual_parkno', '1', old('manual_parkno')) !!}
            </div>
        </div>

        <br/>

        <div class="cf">
            <div class="width50 left">
                <?php $defaultChargeMethod = old('bike_charge_method', $parkingService::CHARGE_METHOD_PER_HOUR); ?>
                <div class="form">
                    <div class="label">Charge Method (Two Wheeler)</div>
                    <div class="input">
                        {!! Form::select('bike_charge_method', $parkingService->getChargeMethods(), $defaultChargeMethod, ['class' => 'charge-method', 'data-type' => 'bike']) !!}
                    </div>
                </div>

                <div class="bike-charge-method-per-hour" {!! $defaultChargeMethod == $parkingService::CHARGE_METHOD_PER_HOUR ? ' style="display:block"' : ' style="display:none"' !!}>
                    <div class="form">
                        <div class="label">Charge Per Hour &#8377;</div>
                        <div class="input">
                            {!! Form::text('bike_charge_per_hour', old('bike_charge_per_hour')) !!}
                        </div>
                    </div>
                </div>
                <div class="bike-charge-method-in-category" {!! $defaultChargeMethod == $parkingService::CHARGE_METHOD_IN_CATEGORY ? ' style="display:block"' : ' style="display:none"' !!}>
                    <div class="form">
                        <div class="label">Charge Between</div>
                        @for ($i = 1; $i <= $chargeMethodInCategoryCount; $i++)
                            <div class="input">
                                {!! Form::text('bike_charge_'.$i.'_min', old('bike_charge_'.$i.'_min')) !!} -
                                &#8377; {!! Form::text('bike_charge_'.$i, old('bike_charge_'.$i)) !!}
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="form">
                    <div class="label">Charge 24hr. Max &#8377;</div>
                    <div class="input">
                        {!! Form::text('bike_charge_max', old('bike_charge_max')) !!}
                    </div>
                </div>
            </div>

            <div class="width50 left">
                <?php $defaultChargeMethod = old('car_charge_method', $parkingService::CHARGE_METHOD_PER_HOUR); ?>
                <div class="form">
                    <div class="label">Charge Method (Four Wheeler)</div>
                    <div class="input">
                        {!! Form::select('car_charge_method', $parkingService->getChargeMethods(), $defaultChargeMethod, ['class' => 'charge-method', 'data-type' => 'car']) !!}
                    </div>
                </div>

                <div class="car-charge-method-per-hour" {!! $defaultChargeMethod == $parkingService::CHARGE_METHOD_PER_HOUR ? ' style="display:block"' : ' style="display:none"' !!}>
                    <div class="form">
                        <div class="label">Charge Per Hour &#8377;</div>
                        <div class="input">
                            {!! Form::text('car_charge_per_hour', old('car_charge_per_hour')) !!}
                        </div>
                    </div>
                </div>
                <div class="car-charge-method-in-category" {!! $defaultChargeMethod == $parkingService::CHARGE_METHOD_IN_CATEGORY ? ' style="display:block"' : ' style="display:none"' !!}>
                    <div class="form">
                        <div class="label">Charge Between</div>
                        @for ($i = 1; $i <= $chargeMethodInCategoryCount; $i++)
                            <div class="input">
                                {!! Form::text('car_charge_'.$i.'_min', old('car_charge_'.$i.'_min')) !!} -
                                &#8377; {!! Form::text('car_charge_'.$i, old('car_charge_'.$i)) !!}
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="form">
                    <div class="label">Charge 24hr. Max &#8377;</div>
                    <div class="input">
                        {!! Form::text('car_charge_max', old('car_charge_max')) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form">
            {!! Form::submit('Create') !!}
        </div>
    {!! Form::close() !!}
@stop