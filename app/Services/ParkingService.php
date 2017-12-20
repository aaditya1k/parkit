<?php

namespace App\Services;

use App\Parking;
use App\Parked;

class ParkingService
{
    const CHARGE_METHOD_PER_HOUR = 1;
    const CHARGE_METHOD_IN_CATEGORY = 2;

    const TOOL_CAR = 4;

    const TOOLS = [
        0 => 'fa-long-arrow-up',
        1 => 'fa-long-arrow-down',
        2 => 'fa-long-arrow-left',
        3 => 'fa-long-arrow-right',
        self::TOOL_CAR => 'fa-car',
        5 => 'fa-motorcycle',
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

    public function getChargeMethods()
    {
        return $this->chargeMethods;
    }

    public function getChargeMethod($index)
    {
        return $this->chargeMethods[$index];
    }

    public function generateMap($rows, $cols, $mapIndexes)
    {
        $html = "";
        $totalI = 0;
        for ($i = 0; $i < $rows; $i++) {
            $html .= '<div class="gen-m-rows">';

            for ($j = 0; $j < $cols; $j++) {

                if ($mapIndexes[$totalI] != null) {
                    $html .= '<div class="gen-m-cols">';
                    $html .= '<i class="fa '.self::TOOLS[ $mapIndexes[$totalI] ].'" aria-hidden="true"></i>';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="gen-m-cols gen-invi"></div>';
                }

                ++$totalI;
            }
            $html .= '</div>';
        }
        return $html;
    }

    // public function getParkingPosition($levels, $positionsFilled, $lastEntries)
    // public function getParkingPosition($vehicleType, Parking $parking)
    // {
    //     // $ground = '[null,"9","4","4","4","4","4","4","1",null,null,"3",null,null,null,null,null,"4","4","4","4","4","4","1",null,"4","4","4","4","4","4",null,"1",null,null,"3",null,null,null,"1",null,"4","4","4","4","4","4",null,null,"4","4","4","4","4","4","1","1",null,null,null,null,null,"9","1"]';
    //     // $level1 = '[null,"9","4","4","4","4","4","4","1",null,null,"3",null,null,null,null,null,"4","4","4","4","4","4","1",null,"4","4","4","4","4","4",null,"1",null,null,"3",null,null,null,"1",null,"4","4","4","4","4","4",null,null,"4","4","4","4","4","4","1","1",null,null,null,null,null,"9","1"]';

    //     // $positionsFilled = [
    //     //     2, 3, 4, 19, 20, 21
    //     // ];

    //     // $parkingService->evaluateSpace([
    //     //     $ground,
    //     //     $level1
    //     // ], $positionsFilled, $lastEntries);

    //     if ($vehicleType == self::VEHICLE_TWO || $parking->manual_parkno == "1") {
    //         // $parked = Parked::create([
    //         //     'parking_id' => $parking->id,
    //         //     'group_id' => $parking->group_id,
    //         //     'position' => null,
    //         //     'vehicle_type' => $vehicleType
    //         // ]);
    //         return [true, [
    //             'parking_id' => $parking->id,
    //             'group_id' => $parking->group_id,
    //             'position' => null,
    //             'vehicle_type' => $vehicleType
    //         ]];
    //     }
    //     exit('s');
    //     $totalCars = [];
    //     $blockI = 0;
    //     $carI = 0;
    //     foreach ($parking->parkingLevels as $level) {
    //         $level = json_decode($level);
    //         foreach ($level as $block) {
    //             if ($block == self::TOOL_CAR) {
    //                 $totalCars[] = [
    //                     'position' => $blockI,
    //                     'available' => (in_array($blockI, $positionsFilled) ? 0 : 1)
    //                 ];
    //             }
    //             ++$blockI;
    //         }
    //     }

    //     if ($positionsFilled == count($totalCars)) {
    //         return [false, "no_space_available"];
    //     }

    //     $maxCarinZone = floor($carI / 3);

    //     if ($averageTime < 180) {
    //         $zone = 1;
    //     } elseif ($averageTime < 480) {
    //         $zone = 2;
    //     } else {
    //         $zone = 3;
    //     }

    //     while (true) {
    //         $getSpace = $this->getSpace($totalCars, $zone, $maxCarinZone);
    //         if ($getSpace !== false) {
    //             return [true, $getSpace];
    //         }
    //         ++$zone;
    //         if ($zone >= 4) {
    //             return [false, "no_space_available"];
    //         }
    //     }
    // }

    public function getQrImage($image, $vehicleType)
    {
        return 'images/'.$image.'-'.self::VEHICLES[$vehicleType].'.png';
    }
}
