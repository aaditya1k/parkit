<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminTrait;
use App\Parking;
use App\ParkingLevel;
use Lang;
use App\Services\ParkingService;

class ParkingLevelController extends Controller
{
    use AdminTrait;

    private $rules = [
        'label' => 'required',
        'grid_console' => 'required'
    ];

    public function index()
    {
        $parkingLevels = ParkingLevel::paginate(20);
        return view('admin.parking-level.index', ['parkingLevels' => $parkingLevels]);
    }

    public function new($parkingId)
    {
        $parking = Parking::findOrFail($parkingId);
        return view('admin.parking-level.new', [
            'parking' => $parking
        ]);
    }

    public function create(Request $request, $parkingId)
    {
        $parking = Parking::findOrFail($parkingId);
        $this->valid($request);

        $grid = json_decode($request->grid_console);

        if ($grid == null ||
            !isset($grid->rows) || !isset($grid->cols) || !isset($grid->map)
        ) {
            return redirect()->back()->
                withErrors(["Please generate grid."]);
        }

        $parkingLevel = ParkingLevel::create([
            'label' => $request->label,
            'parking_id' => $parking->id,
            'grid_row' => $grid->rows,
            'grid_col' => $grid->cols,
            'grid_map' => json_encode($grid->map),
            'generated_map' => null
        ]);

        return redirect()->route('admin:parking-level:edit', $parkingLevel->id)
            ->with('success', Lang::get('admin.created'));
    }

    public function view($id)
    {
        $parkingLevel = ParkingLevel::findOrFail($id);
        return view('admin.parking-level.view', ['parkingLevel' => $parkingLevel]);
    }

    public function edit($id)
    {
        $parkingLevel = ParkingLevel::findOrFail($id);
        return view('admin.parking-level.edit', [
            'parkingLevel' => $parkingLevel
        ]);
    }

    public function update(Request $request, $id)
    {
        $parkingLevel = ParkingLevel::findOrFail($id);
        $this->valid($request);

        $grid = json_decode($request->grid_console);

        if ($grid == null ||
            !isset($grid->rows) || !isset($grid->cols) || !isset($grid->map)
        ) {
            return redirect()->back()->
                withErrors(["Please generate grid."]);
        }

        $parkingLevel->label = $request->label;
        $parkingLevel->grid_row = $grid->rows;
        $parkingLevel->grid_col = $grid->cols;
        $parkingLevel->grid_map = json_encode($grid->map);
        $parkingLevel->save();

        return redirect()->route('admin:parking-level:edit', $parkingLevel->id)
            ->with('success', Lang::get('admin.updated'));
    }
}
