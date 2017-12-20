<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminTrait;
use App\Parking;
use App\ParkingLevel;
use Lang;
use App\Services\ParkingService;
use PHPQRCode\QRcode;

class ParkingController extends Controller
{
    use AdminTrait;

    const CHARGE_METHOD_IN_CATEGORY_COUNT = 4;

    private $rules = [
        'group_id' => 'required|exists:groups,id',
        'label' => 'required',
        'bike_charge_method' => 'required',
        'bike_charge_max' => 'required|numeric',
        'bike_charge_per_hour' => 'required_if:charge_method,1|sometimes|nullable|numeric',
        'car_charge_method' => 'required',
        'car_charge_max' => 'required|numeric',
        'car_charge_per_hour' => 'required_if:charge_method,1|sometimes|nullable|numeric'
    ];

    public function __construct()
    {
        for ($i = 1; $i <= self::CHARGE_METHOD_IN_CATEGORY_COUNT; $i++) {
            $requiredIf = false;
            if ($i == 0) {
                $requiredIf = true;
            }
            $this->rules['bike_charge_'.$i.'_min'] = ($requiredIf ? 'required_if:charge_method,2|' : null).'sometimes|nullable|numeric';
            $this->rules['bike_charge_'.$i] = ($requiredIf ? 'required_if:charge_method,2|' : null).'sometimes|nullable|numeric';

            $this->rules['car_charge_'.$i.'_min'] = ($requiredIf ? 'required_if:charge_method,2|' : null).'sometimes|nullable|numeric';
            $this->rules['car_charge_'.$i] = ($requiredIf ? 'required_if:charge_method,2|' : null).'sometimes|nullable|numeric';
        }
    }

    public function index(ParkingService $parkingService)
    {
        $parkings = Parking::orderBy('group_id', 'asc')->orderBy('label', 'asc')->paginate(20);
        return view('admin.parking.index', ['parkings' => $parkings]);
    }

    public function new()
    {
        return view('admin.parking.new', [
            'chargeMethodInCategoryCount' => self::CHARGE_METHOD_IN_CATEGORY_COUNT
        ]);
    }

    public function create(Request $request, ParkingService $parkingService)
    {
        $chargeMethods = array_keys($parkingService->getChargeMethods());
        $this->valid($request);

        $bikeChargeJson = $this->formatChargeMethod($request, 'bike_');
        if ($bikeChargeJson === false) {
            return abort(404);
        }

        $carChargeJson = $this->formatChargeMethod($request, 'car_');
        if ($carChargeJson === false) {
            return abort(404);
        }

        $exitKey = str_random(40);

        $parking = Parking::create([
            'group_id' => $request->group_id,
            'label' => $request->label,
            'secret_key' => str_random(40),
            'exit_generated_key' => $exitKey,
            'manual_parkno' => $request->manual_parkno == "1" ? "1" : "0",
            'bike_charge_method' => $request->bike_charge_method,
            'bike_charge_json' => json_encode($bikeChargeJson),
            'bike_charge_max' => $request->bike_charge_max,
            'car_charge_method' => $request->car_charge_method,
            'car_charge_json' => json_encode($carChargeJson),
            'car_charge_max' => $request->car_charge_max,
        ]);

        $entryImage = $parking->id.'-ent-'.str_random(20);
        $exitImage = $parking->id.'-ext-'.str_random(20);

        $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_TWO];
        QRcode::png(json_encode($qrInfo), public_path($parkingService->getQrImage($entryImage, ParkingService::VEHICLE_TWO)), 'M', 8, 2);
        $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_FOUR];
        QRcode::png(json_encode($qrInfo), public_path($parkingService->getQrImage($entryImage, ParkingService::VEHICLE_FOUR)), 'M', 8, 2);

        $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_TWO, 'exit_generated_key' => $exitKey];
        QRcode::png(json_encode($qrInfo), public_path($parkingService->getQrImage($exitImage, ParkingService::VEHICLE_TWO)), 'M', 8, 2);
        $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_FOUR, 'exit_generated_key' => $exitKey];
        QRcode::png(json_encode($qrInfo), public_path($parkingService->getQrImage($exitImage, ParkingService::VEHICLE_FOUR)), 'M', 8, 2);

        $parking->entry_image = $entryImage;
        $parking->exit_image = $exitImage;
        $parking->save();

        return redirect()->route('admin:parking:edit', $parking->id)->with('success', Lang::get('admin.created'));
    }

    public function view($id)
    {
        $parking = Parking::findOrFail($id);
        $parkingLevels = ParkingLevel::getParking($parking->id)->get();
        return view('admin.parking.view', ['parking' => $parking, 'parkingLevels' => $parkingLevels]);
    }

    public function edit($id)
    {
        $parking = Parking::findOrFail($id);
        return view('admin.parking.edit', [
            'chargeMethodInCategoryCount' => self::CHARGE_METHOD_IN_CATEGORY_COUNT,
            'parking' => $parking
        ]);
    }

    public function update(Request $request, ParkingService $parkingService, $id)
    {
        $parking = Parking::findOrFail($id);

        $chargeMethods = array_keys($parkingService->getChargeMethods());
        $this->valid($request);

        $bikeChargeJson = $this->formatChargeMethod($request, 'bike_');
        if ($bikeChargeJson === false) {
            return abort(404);
        }

        $carChargeJson = $this->formatChargeMethod($request, 'car_');
        if ($carChargeJson === false) {
            return abort(404);
        }

        $parking->group_id = $request->group_id;
        $parking->label = $request->label;
        if ($request->regenrate_secret_key == "1") {
            $parking->secret_key = str_random(40);
        }
        $parking->manual_parkno = $request->manual_parkno == "1" ? "1" : "0";
        $parking->bike_charge_method = $request->bike_charge_method;
        $parking->bike_charge_json = json_encode($bikeChargeJson);
        $parking->bike_charge_max = $request->bike_charge_max;
        $parking->car_charge_method = $request->car_charge_method;
        $parking->car_charge_json = json_encode($carChargeJson);
        $parking->car_charge_max = $request->car_charge_max;
        $parking->save();

        return redirect()->route('admin:parking:edit', $parking->id)->with('success', Lang::get('admin.updated'));
    }

    private function formatChargeMethod($request, $type)
    {
        $chargeJson = [];
        if ($request->{$type.'charge_method'} == ParkingService::CHARGE_METHOD_PER_HOUR) {
            $chargeJson['charge_per_hour'] = $request->{$type.'charge_per_hour'};
        } elseif ($request->{$type.'charge_method'} == ParkingService::CHARGE_METHOD_IN_CATEGORY) {
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
}
