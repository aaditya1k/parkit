<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parking;
use App\Services\ParkingService;

/**
 * Class only for demo purposes.
 */
class DemoController extends Controller
{
    private $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    /**
     * Demo entry qr code screen.
     */
    public function entry($id, $vehicleType)
    {
        try {
            $parking = Parking::findOrFail($id);
            if ($vehicleType != ParkingService::VEHICLE_TWO && $vehicleType != ParkingService::VEHICLE_FOUR) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Vehicle Type.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ]);
        }
        return view('demo.qr', [
            'parking' => $parking,
            'vehicleType' => $vehicleType,
            'type' => 'Entry',
            'image' => $this->parkingService->getQrImage($parking->entry_image, $vehicleType),
        ]);
    }

    /**
     * Demo exit qr code screen.
     */
    public function exit($id, $vehicleType, $secretKey)
    {
        try {
            $parking = Parking::findOrFail($id);
            if ($vehicleType != ParkingService::VEHICLE_TWO && $vehicleType != ParkingService::VEHICLE_FOUR) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Vehicle Type.'
                ]);
            }
            if ($parking->secret_key !== $secretKey) {
                return "Invalid secrey key for exit qr code.";
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ]);
        }
        return view('demo.qr', [
            'parking' => $parking,
            'vehicleType' => $vehicleType,
            'type' => 'Exit',
            'image' => $this->parkingService->getQrImage($parking->exit_image, $vehicleType),
        ]);
    }

    /**
     * Routes
     */
    public function routes()
    {
        $routes = \Route::getRoutes();
        $categorize = [];
        foreach ($routes as $route) {
            $splitAs = explode(':', $route->action['as']);
            $splitLen = count($splitAs);
            if ($splitLen > 1) {
                if (isset($categorize[ $splitAs[0] ])) {
                    if (isset($categorize[ $splitAs[0] ]) && isset($categorize[ $splitAs[0] ][ $splitAs[1] ])) {
                        $categorize[ $splitAs[0] ][ $splitAs[1] ][] = $route;
                    } else {
                        $categorize[ $splitAs[0] ][ $splitAs[1] ] = [$route];
                    }
                } else {
                    $categorize[ $splitAs[0] ][ $splitAs[1] ] = [$route];
                }
            } elseif ($splitLen === 1 || $splitLen === 2) {
                if (isset($categorize[ $splitAs[0] ])) {
                    $categorize[ $splitAs[0] ][] = $route;
                } else {
                    $categorize[ $splitAs[0] ] = [$route];
                }
            }
        }
        return view('demo.routes', [
            'categorize' => $categorize
        ]);
    }
}
