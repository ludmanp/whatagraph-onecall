<?php

namespace App\Services\OpenWeatherApi;

use App\Data\LocationData;
use App\Data\WeatherData;
use App\Services\OpenWeatherApiService;

class OneCallService extends OpenWeatherApiService
{
    /**
     * @param LocationData $location
     * @return WeatherData
     */
    public function getCurrentWeather(LocationData $location): ?WeatherData
    {
        $response = $this->send(
            config('services.openweathermap.onecall_url'),
            ['lat' => $location->lat, 'lon' => $location->lon, 'units' => 'metric', 'exclude' => 'minutely,hourly,daily,alerts']
        );

        $current = data_get($response, 'current');
        if(!empty($current)) {
            $current['location'] = $location;
            return WeatherData::from($current);
        }
    }
}
