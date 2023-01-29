<?php

namespace App\Services\OpenWeatherApi;

use App\Data\LocationData;
use App\Services\OpenWeatherApiService;

class GeocodingService extends OpenWeatherApiService
{
    public function getLocation(string $name): ?LocationData
    {
        $response = $this->send(config('services.openweathermap.geocoding_url'), ['q' => $name, 'limit' => 1]);

        $firstLocation = data_get($response, 0);
        if(!empty($firstLocation)) {
            return LocationData::from($firstLocation);
        }
    }
}
