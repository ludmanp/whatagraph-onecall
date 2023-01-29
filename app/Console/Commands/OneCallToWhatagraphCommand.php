<?php

namespace App\Console\Commands;

use App\Data\LocationData;
use App\Services\OpenWeatherApi\GeocodingService;
use App\Services\OpenWeatherApi\OneCallService;
use App\Services\WhataGrapthApiService;
use Illuminate\Console\Command;

/**
 * Each represented location is converted to geodata using Geocoding API,
 * OneCall API is requested for current weather data
 * and after transformation data are pushed to WhataGraph API
 */
class OneCallToWhatagraphCommand extends Command
{
    protected $signature = 'whatagraph:one-call {locations*}';

    protected $description = 'Got data from One Call API and send it to WhataGraph API';

    public function __construct(
        private GeocodingService $geocodingService,
        private OneCallService $oneCallService,
        private WhataGrapthApiService $whataGrapthApiService
    )
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $locations = $this->argument('locations');

        foreach ($locations as $locationName)
        {
            if($location = $this->geocodingService->getLocation($locationName)) {
                $this->processLocation($location);
            }
        }
    }

    /**
     * @param LocationData $location
     * @return void
     */
    private function processLocation(LocationData $location): void
    {
        $weather = $this->oneCallService->getCurrentWeather($location);
        $this->whataGrapthApiService->pushData($weather);
    }
}
