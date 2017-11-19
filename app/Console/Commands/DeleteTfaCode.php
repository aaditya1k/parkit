<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TfaCode;
use Carbon\Carbon;

class DeleteTfaCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:tfacodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes Expired TFA Codes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deleted = TfaCode::where('created_at', '<', Carbon::now()->subMinutes(1))->delete();
        $this->info("Deleted: " . $deleted);
    }
}
