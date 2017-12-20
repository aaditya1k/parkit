<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Parking;
use PHPQRCode\QRcode;
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

            $entryImage = $parking->id.'-ent-'.str_random(20);
            $exitImage = $parking->id.'-ext-'.str_random(20);
            $exitKey = str_random(40);

            $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_TWO];
            QRcode::png(json_encode($qrInfo), public_path($this->parkingService->getQrImage($entryImage, ParkingService::VEHICLE_TWO)), 'M', 8, 2);
            $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_FOUR];
            QRcode::png(json_encode($qrInfo), public_path($this->parkingService->getQrImage($entryImage, ParkingService::VEHICLE_FOUR)), 'M', 8, 2);

            $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_TWO, 'exit_generated_key' => $exitKey];
            QRcode::png(json_encode($qrInfo), public_path($this->parkingService->getQrImage($exitImage, ParkingService::VEHICLE_TWO)), 'M', 8, 2);
            $qrInfo = ['id' => $parking->id, 'vehicleType' => ParkingService::VEHICLE_FOUR, 'exit_generated_key' => $exitKey];
            QRcode::png(json_encode($qrInfo), public_path($this->parkingService->getQrImage($exitImage, ParkingService::VEHICLE_FOUR)), 'M', 8, 2);

            $parking->entry_image = $entryImage;
            $parking->exit_image = $exitImage;
            $parking->exit_generated_key = $exitKey;
            $parking->save();

            @unlink(public_path($this->parkingService->getQrImage($currentEntryImg, ParkingService::VEHICLE_TWO)));
            @unlink(public_path($this->parkingService->getQrImage($currentEntryImg, ParkingService::VEHICLE_FOUR)));
            @unlink(public_path($this->parkingService->getQrImage($currentExitImg, ParkingService::VEHICLE_TWO)));
            @unlink(public_path($this->parkingService->getQrImage($currentExitImg, ParkingService::VEHICLE_FOUR)));
        }
    }
}
