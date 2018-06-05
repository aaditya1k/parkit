<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parking;
use App\Services\HelperService;
use App\Services\ParkingService;

class ParkingController extends Controller
{
    /**
     * Get list of parkings.
     */
    public function index(Request $request)
    {
        $parkings = Parking::paginate(20);
        return response()->json([
            'success' => true,
            'parkings' => $parkings
        ]);
    }

    /**
     * Search for a parking by name.
     */
    public function search(Request $request)
    {
        $keyword = HelperService::escapeLike(mb_strtolower(trim($request->label), 'UTF-8'));
        $paginateAppend = [];
        $parkings = Parking::orderBy('label', 'asc');

        $parkings->whereRaw(
            'lower(`label`) like ?',
            ['%'.$keyword.'%']
        );

        $paginateAppend['search'] = $keyword;
        $parkings = $parkings->paginate(20);
        $parkings->appends($paginateAppend);

        return response()->json([
            'success' => true,
            'parkings' => $parkings
        ]);
    }

    /**
     * Get info about a parking.
     */
    public function parking($id)
    {
        try {
            $parking = Parking::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }
        $parking->parkingLevels;
        return response()->json([
            'success' => true,
            'parking' => $parking
        ]);
    }

    /**
     * Get image url of qr code of entrance.
     */
    public function entryQrCode(Request $request, ParkingService $parkingService)
    {
        $id = $request->id;
        $vehicleType = $request->vehicleType;
        try {
            $parking = Parking::findOrFail($id);
            if ($vehicleType != ParkingService::VEHICLE_TWO && $vehicleType != ParkingService::VEHICLE_FOUR) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Vehicle Type.'
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => asset($parkingService->getQrImage($parking->entry_image, $vehicleType))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }
    }

    /**
     * Get image url of qr code of entrance.
     */
    public function exitQrCode(Request $request, ParkingService $parkingService)
    {
        $id = $request->id;
        $vehicleType = $request->vehicleType;
        $secretKey = $request->secretKey;
        try {
            $parking = Parking::findOrFail($id);
            if ($vehicleType != ParkingService::VEHICLE_TWO && $vehicleType != ParkingService::VEHICLE_FOUR) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Vehicle Type.'
                ]);
            }
            if ($parking->secret_key != $secretKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid key.'
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => asset($parkingService->getQrImage($parking->exit_image, $vehicleType))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '404'
            ], 404);
        }
    }
}
