<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Services\ParkingService;
use App\Parking;

class ParkController extends Controller
{
    private $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function park(Request $request)
    {
        $vehicleType = intval($request->vehicleType);
        $parkingId = intval($request->id);

        if (!in_array($vehicleType, [ParkingService::VEHICLE_TWO, ParkingService::VEHICLE_FOUR])) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }

        try {
            $parking = Parking::findOrFail($parkingId);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }

        $response = $this->parkingService->getParkingPosition($vehicleType, $parking);

        if ($response['success']) {
            return response()->json([
                'success' => true,
                'position' => $response['parked']->position,
                'level' => $response['level']->label
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response['message']
            ]);
        }
    }

    public function unpark(Request $request)
    {
        $vehicleType = intval($request->vehicleType);
        $parkingId = intval($request->id);
        $exitGeneratedKey = $request->exit_generated_key;

        if (!in_array($vehicleType, [ParkingService::VEHICLE_TWO, ParkingService::VEHICLE_FOUR])) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }

        $parking = Parking::where([
            'id' => $parkingId,
            'exit_generated_key' => $exitGeneratedKey
        ])->first();

        if (!$parking) {
            return response()->json([
                'success' => false,
                'message' => 'Please check with support'
            ], 403);
        }

        $response = $this->parkingService->unPark($parking, $vehicleType, \Auth::user()->id);

        if ($response['success']) {
            return response()->json([
                'success' => true,
                'charge' => $response['charge'],
                'days' => $response['days'],
                'hours' => $response['hours'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response['message']
            ]);
        }
    }
}
