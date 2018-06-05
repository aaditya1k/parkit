<?php

namespace App\Services;

use App\Parking;
use App\Parked;
use App\Group;
use App\User;
use Auth;
use PHPQRCode\QRcode;
use Carbon\Carbon;
use DB;

class ParkingService
{
    const CHARGE_METHOD_PER_HOUR = 1;
    const CHARGE_METHOD_IN_CATEGORY = 2;

    const CHARGE_METHOD_IN_CATEGORY_COUNT = 4;

    const TOOL_CAR = 4;
    const TOOL_BIKE = 5;

    const TOOLS = [
        0 => 'fa-long-arrow-up',
        1 => 'fa-long-arrow-down',
        2 => 'fa-long-arrow-left',
        3 => 'fa-long-arrow-right',
        self::TOOL_CAR => 'fa-car',
        self::TOOL_BIKE => 'fa-motorcycle',
        6 => 'fa-sign-in',
        7 => 'fa-sign-out',
        8 => 'fa-stop',
        9 => 'fa-id-card-o',
        10 => '',
    ];

    const VEHICLE_TWO = 1;
    const VEHICLE_FOUR = 2;

    const VEHICLES = [
        self::VEHICLE_TWO => 'two',
        self::VEHICLE_FOUR => 'four'
    ];

    private $chargeMethods = [
        self::CHARGE_METHOD_PER_HOUR => 'Per Hour',
        self::CHARGE_METHOD_IN_CATEGORY => 'In Category'
    ];

    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function getChargeMethods()
    {
        return $this->chargeMethods;
    }

    public function getChargeMethod($index)
    {
        return $this->chargeMethods[$index];
    }

    public function generateMap($rows, $cols, $mapIndexes, $carNumber = 0, $bikeNumber = 0, $parkedList = null)
    {
        $positionsFilled = [
            self::TOOL_CAR => [],
            self::TOOL_BIKE => []
        ];
        if ($parkedList != null) {
            foreach ($parkedList as $parked) {
                $positionIndex = $parked->vehicle_type == self::VEHICLE_TWO
                    ? self::TOOL_BIKE
                    : self::TOOL_CAR;
                $positionsFilled[$positionIndex][] = $parked->position;
            }
        }

        $html = "";
        $runningBlock = 0;
        for ($i = 0; $i < $rows; $i++) {
            $html .= '<div class="gen-m-rows">';
            for ($j = 0; $j < $cols; $j++) {
                $blockKey = $mapIndexes[$runningBlock];
                if ($blockKey != null) {
                    $extendClass = null;
                    $title = null;
                    if ($blockKey == self::TOOL_CAR) {
                        ++$carNumber;
                        if (in_array($carNumber, $positionsFilled[$blockKey])) {
                            $extendClass = ' gen-m-cols-filled';
                        }
                        $title = 'title="'.$carNumber.'"';
                    }
                    if ($blockKey == self::TOOL_BIKE) {
                        ++$bikeNumber;
                        if (in_array($bikeNumber, $positionsFilled[$blockKey])) {
                            $extendClass = ' gen-m-cols-filled';
                        }
                        $title = 'title="'.$bikeNumber.'"';
                    }
                    $html .= '<div class="gen-m-cols'.$extendClass.'">';
                    $html .= '<i '.$title.' class="fa '.self::TOOLS[ $blockKey ].'" aria-hidden="true"></i>';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="gen-m-cols gen-invi"></div>';
                }
                ++$runningBlock;
            }
            $html .= '</div>';
        }
        return [
            'carNumber' => $carNumber,
            'bikeNumber' => $bikeNumber,
            'html' => $html
        ];
    }

    public function generateEntryExitQRCodes($parkingId)
    {
        $entryImage = $parkingId.'-ent-'.str_random(20);
        $exitImage = $parkingId.'-ext-'.str_random(20);
        $exitGeneratedKey = str_random(40);

        $qrInfo = ['id' => $parkingId, 'vehicleType' => self::VEHICLE_TWO];
        QRcode::png(json_encode($qrInfo), public_path($this->getQrImage($entryImage, self::VEHICLE_TWO)), 'M', 8, 2);
        $qrInfo = ['id' => $parkingId, 'vehicleType' => self::VEHICLE_FOUR];
        QRcode::png(json_encode($qrInfo), public_path($this->getQrImage($entryImage, self::VEHICLE_FOUR)), 'M', 8, 2);

        $qrInfo = ['id' => $parkingId, 'vehicleType' => self::VEHICLE_TWO, 'exit_generated_key' => $exitGeneratedKey];
        QRcode::png(json_encode($qrInfo), public_path($this->getQrImage($exitImage, self::VEHICLE_TWO)), 'M', 8, 2);
        $qrInfo = ['id' => $parkingId, 'vehicleType' => self::VEHICLE_FOUR, 'exit_generated_key' => $exitGeneratedKey];
        QRcode::png(json_encode($qrInfo), public_path($this->getQrImage($exitImage, self::VEHICLE_FOUR)), 'M', 8, 2);

        return [
            'entryImage' => $entryImage,
            'exitImage' => $exitImage,
            'exitGeneratedKey' => $exitGeneratedKey
        ];
    }

    public function formatChargeMethod($request, $type)
    {
        $chargeJson = [];
        if ($request->{$type.'charge_method'} == self::CHARGE_METHOD_PER_HOUR) {
            $chargeJson['charge_per_hour'] = $request->{$type.'charge_per_hour'};
        } elseif ($request->{$type.'charge_method'} == self::CHARGE_METHOD_IN_CATEGORY) {
            for ($i = 1; $i <= self::CHARGE_METHOD_IN_CATEGORY_COUNT; $i++) {
                if ($request->{$type.'charge_'.$i.'_min'} != null
                    && $request->{$type.'charge_'.$i} != null
                ) {
                    $chargeJson[] = [
                        'min' => $request->{$type.'charge_'.$i.'_min'},
                        'charge' => $request->{$type.'charge_'.$i}
                    ];
                }
            }
        } else {
            return false;
        }
        return $chargeJson;
    }

    public function getParkingPosition($vehicleType, Parking $parking)
    {
        $levels = $parking->parkingLevels;
        if (count($levels)  === 0) {
            return [
                'success' => false,
                'message' => 'Parking is under construction'
            ];
        }

        if ($parking->manual_parkno == 1) {
            $parked = Parked::create([
                'parking_id' => $parking->id,
                'parking_level_id' => null,
                'user_id' => Auth::user()->id,
                'group_id' => $parking->group_id,
                'position' => null,
                'vehicle_type' => $vehicleType
            ]);
            $this->activityService->create(
                ActivityService::ACTIVITY_PARKING_ENTRY,
                Auth::user()->id,
                ['parkedId' => $parked->id, 'parking' => $parking->id],
                null
            );
            return [
                'success' => true,
                'parked' => $parked,
                'manual_parkno' => 1
            ];
        }

        if ($vehicleType == self::VEHICLE_TWO) {
            $vehicleToolCode = self::TOOL_BIKE;
        } else {
            $vehicleToolCode = self::TOOL_CAR;
        }

        $positionsFilled = [
            self::TOOL_CAR => [],
            self::TOOL_BIKE => []
        ];
        $parkedList = $parking->parkedList;
        foreach ($parkedList as $parked) {
            $positionIndex = $parked->vehicle_type === self::VEHICLE_TWO
                ? self::TOOL_BIKE
                : self::TOOL_CAR;
            $positionsFilled[$positionIndex][] = $parked->position;
        }

        $vehicleCount = [
            self::TOOL_CAR => 0,
            self::TOOL_BIKE => 0
        ];
        foreach ($levels as $level) {
            $mapped = json_decode($level->grid_map);
            foreach ($mapped as $block) {
                if ($block == self::TOOL_CAR) {
                    $vehicleCount[self::TOOL_CAR] = $vehicleCount[self::TOOL_CAR] + 1;
                }
                if ($block == self::TOOL_BIKE) {
                    $vehicleCount[self::TOOL_BIKE] = $vehicleCount[self::TOOL_BIKE] + 1;
                }
                if ($block == $vehicleToolCode
                    && !in_array($vehicleCount[$vehicleToolCode], $positionsFilled[$vehicleToolCode])
                ) {
                    $parked = Parked::create([
                        'parking_id' => $parking->id,
                        'parking_level_id' => $level->id,
                        'user_id' => Auth::user()->id,
                        'group_id' => $parking->group_id,
                        'position' => $vehicleCount[$vehicleToolCode],
                        'vehicle_type' => $vehicleType
                    ]);
                    $this->activityService->create(
                        ActivityService::ACTIVITY_PARKING_ENTRY,
                        Auth::user()->id,
                        ['parkedId' => $parked->id, 'parking' => $parking->id],
                        null
                    );
                    return [
                        'success' => true,
                        'parked' => $parked,
                        'level' => $level,
                        'manual_parkno' => 0
                    ];
                }
            }
        }

        return [
            'success' => false,
            'message' => 'Parking space not available.'
        ];
    }

    public function unPark($parking, $vehicleType, $userId)
    {
        $parked = Parked::where('parking_id', $parking->id)
            ->where('user_id', $userId)
            ->where('vehicle_type', $vehicleType)
            ->where('exited_at', null)
            ->first();
        if (!$parked) {
            return [
                'success' => false,
                'message' => 'Already exited with this ticket.'
            ];
        }
        $hours = Carbon::now()->diffInHours($parked->created_at) + 1;
        if ($parked->vehicle_type == self::VEHICLE_TWO) {
            $calculateCharge = $this->calculateCharge(
                $hours,
                $parking->bike_charge_method,
                $parking->bike_charge_json,
                $parking->bike_charge_max
            );
            $charge = $calculateCharge['charge'];
        } else {
            $calculateCharge = $this->calculateCharge(
                $hours,
                $parking->car_charge_method,
                $parking->car_charge_json,
                $parking->car_charge_max
            );
            $charge = $calculateCharge['charge'];
        }

        DB::beginTransaction();

        $deductBalance = User::where('id', $parked->user_id)
            ->where('balance', '>=', $charge)
            ->decrement('balance', $charge);
        $updated = $parked->where('parking_id', $parking->id)
            ->where('id', $parked->id)
            ->where('user_id', $userId)
            ->where('vehicle_type', $vehicleType)
            ->where('exited_at', null)
            ->update([
                'exit_charges' => $charge,
                'exited_at' => Carbon::now()
            ]);
        if (!$updated) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Already exited with this ticket.'
            ];
        } elseif (!$deductBalance) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Insufficient funds',
                'charge_log' => $calculateCharge
            ];
        }

        DB::commit();
        $this->activityService->create(
            ActivityService::ACTIVITY_PARKING_EXIT,
            $userId,
            ['parkedId' => $parked->id, 'parking' => $parking->id, 'charge' => $charge],
            null
        );
        Group::where('id', $parking->group_id)
            ->increment('balance', $charge);
        return [
            'success' => true,
            'charge' => $charge,
            'days' => $calculateCharge['days'],
            'hours' => $calculateCharge['hours'],
        ];
    }

    public function getQrImage($image, $vehicleType)
    {
        return 'images/'.$image.'-'.self::VEHICLES[$vehicleType].'.png';
    }

    private function calculateCharge($hours, $method, $chargeJson, $dayMaxCharge)
    {
        $days = floor($hours / 24);
        $hours = $hours - 24 * $days;

        $totalCharge = 0;
        if ($method == self::CHARGE_METHOD_PER_HOUR) {
            $totalCharge = $hours * $chargeJson->charge_per_hour;
            $totalCharge += $days * $dayMaxCharge;
        } else {
            $found = false;
            foreach ($chargeJson as $cat) {
                if ($hours <= $cat->min) {
                    $found = true;
                    $totalCharge = $cat->charge;
                    $totalCharge += $days * $dayMaxCharge;
                }
            }
            if (!$found) {
                // Use last charge (maximum)
                $totalCharge = $dayMaxCharge;
                $totalCharge += $days * $dayMaxCharge;
            }
        }

        return [
            'charge' => $totalCharge,
            'days' => $days,
            'hours' => $hours,
        ];
    }
}
