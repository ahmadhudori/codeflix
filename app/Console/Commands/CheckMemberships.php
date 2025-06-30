<?php

namespace App\Console\Commands;

use App\Jobs\CheckMembershipStatus;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CheckMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Deactived Memberships Expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // CheckMembershipStatus::dispatch();
		// $this->info("Check Deactived Memberships Expired");

		Bus::batch([ new CheckMembershipStatus() ])
		->then( function (Batch $batch) {
			Log::info("Memberships check completed");
		})->catch( function (Batch $batch) {
			Log::error("Memberships check failed");
		})->finally( function (Batch $batch) {
			Log::info("Memberships check finished");
		})->dispatch();
    }
}
