<?php

namespace App\Console\Commands;

use App\Services\WhataGrapthApiService;
use Illuminate\Console\Command;

/**
 * Initiates metrics for WhataGraph based on OneCall response parameters
 */
class InitWhataGraphCommand extends Command
{
    protected $signature = 'whatagraph:init';

    protected $description = 'Creates and updates WhataGraph metrics';

    public function handle(WhataGrapthApiService $whataGrapthApiService)
    {
        $whataGrapthApiService->initMetrics();
    }
}
