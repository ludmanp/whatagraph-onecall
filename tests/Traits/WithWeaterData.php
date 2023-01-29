<?php

namespace Tests\Traits;

use App\Data\LocationData;
use App\Data\WeatherData;

trait WithWeaterData
{
    protected function prepareWeatherArray(): array
    {
        $locationData = LocationData::from([
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
        ]);

        return [
            'dt' => now()->timestamp,
            'sunrise' => now()->timestamp,
            'sunset' => now()->timestamp,
            'temp' => $this->faker->randomFloat(2, -10, 20),
            'feels_like' => $this->faker->randomFloat(2, -10, 20),
            'pressure' => $this->faker->numberBetween(900, 1100),
            'humidity' => $this->faker->numberBetween(10, 100),
            'dew_point' => $this->faker->randomFloat(2, -10, 20),
            'uvi' => $this->faker->randomFloat(2, 0, 1),
            'clouds' => $this->faker->numberBetween(10, 100),
            'visibility' => $this->faker->numberBetween(5000, 10000),
            'wind_speed' => $this->faker->randomFloat(2, 0, 7),
            'wind_deg' => $this->faker->numberBetween(0, 360),
            'location' => $locationData,
        ];
    }

    protected function prepareWeatherData(): WeatherData
    {
        return WeatherData::from($this->prepareWeatherArray());
    }
}
