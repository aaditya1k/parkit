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
        $vehicleType = $request->vehicle;
        $parkingId = $request->id;

        try {
            $parking = Parking::findOrFail($parkingId);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }

        $this->parkingService->getParkingPosition($vehicleType, $parking);
    }

    public function unpark(Request $request)
    {

    }
}
