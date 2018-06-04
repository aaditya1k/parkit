<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Parking;
use App\Services\ParkingService;

class RegenerateParkingKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regenerate:parking-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenrate keys when exiting the parking';

    private $parkingService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParkingService $parkingService)
    {
        parent::__construct();
        $this->parkingService = $parkingService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parkings = Parking::get();
        foreach ($parkings as $parking) {
            $currentEntryImg = $parking->entry_image;
            $currentExitImg = $parking->exit_image;

            $entryExitQRCodes = $this->parkingService->generateEntryExitQRCodes($parking->id);

            $parking->entry_image = $entryExitQRCodes['entryImage'];
            $parking->exit_image = $entryExitQRCodes['exitImage'];
            $parking->exit_generated_key = $entryExitQRCodes['exitGeneratedKey'];
            $parking->save();

            @unlink(public_path($this->parkingService->getQrImage($currentEntryImg, ParkingService::VEHICLE_TWO)));
            @unlink(public_path($this->parkingService->getQrImage($currentEntryImg, ParkingService::VEHICLE_FOUR)));
            @unlink(public_path($this->parkingService->getQrImage($currentExitImg, ParkingService::VEHICLE_TWO)));
            @unlink(public_path($this->parkingService->getQrImage($currentExitImg, ParkingService::VEHICLE_FOUR)));
        }
    }
}
