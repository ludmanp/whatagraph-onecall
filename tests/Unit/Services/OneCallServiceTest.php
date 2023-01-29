<?php

namespace Tests\Unit\Services;

use App\Services\OpenWeatherApi\GeocodingService;
use App\Services\OpenWeatherApi\OneCallService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Services\OpenWeatherApi\OneCallService
 */
class OneCallServiceTest extends TestCase
{
    use WithFaker;

    const URL_ONECALL = 'https://api.openweathermap.org/data/3.0/onecall*';
    const URL_GEOCODING = 'https://api.openweathermap.org/geo/1.0/direct*';

    public function testGetLocationSuccess()
    {

        $response = $this->prepareOneCalResponse();
        $geocodingResponse = $this->prepareGeocodingResponse();

        Http::fake([
            self::URL_ONECALL => Http::response($response),
            self::URL_GEOCODING => Http::response($geocodingResponse),
        ]);

        $localeData = (new GeocodingService)->getLocation('Riga');
        $weatherData = (new OneCallService())->getCurrentWeather($localeData);

        $this->assertEquals($response['current']['temp'], $weatherData->temp);
        $this->assertEquals($response['current']['feels_like'], $weatherData->feels_like);
        $this->assertEquals($response['current']['pressure'], $weatherData->pressure);
        $this->assertEquals($response['current']['humidity'], $weatherData->humidity);
        $this->assertEquals($response['current']['dew_point'], $weatherData->dew_point);
        $this->assertEquals($response['current']['uvi'], $weatherData->uvi);
        $this->assertEquals($response['current']['clouds'], $weatherData->clouds);
        $this->assertEquals($response['current']['visibility'], $weatherData->visibility);
        $this->assertEquals($response['current']['wind_speed'], $weatherData->wind_speed);
        $this->assertEquals($response['current']['wind_deg'], $weatherData->wind_deg);
    }

    public function testGetLocationException()
    {
        $response = $this->prepareOneCalResponse();
        $geocodingResponse = $this->prepareGeocodingResponse();

        Http::fake([
            self::URL_ONECALL => Http::response($response),
            self::URL_GEOCODING => Http::response($geocodingResponse, 400),
        ]);

        $this->expectException(RequestException::class);

        $localeData = (new GeocodingService)->getLocation('Riga');
        (new OneCallService())->getCurrentWeather($localeData);
    }

    private function prepareOneCalResponse(): array
    {
        return [
            'lat' => 56.9494,
            'lon' => 24.1052,
            'timezone' => 'Europe/Riga',
            'timezone_offset' => 7200,
            'current' => [
                'dt' => now()->timestamp,
                'sunrise' => $this->faker->dateTimeBetween(now()->startOfDay(), now())->getTimestamp(),
                'sunset' => $this->faker->dateTimeBetween(now(), now()->endOfDay())->getTimestamp(),
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
                'weather' => [
                    [
                        'id' => $this->faker->numberBetween(100, 999),
                        'main' => 'Clouds',
                        'description' => 'broken clouds',
                        'icon' => '04d'
                    ]
                ]
            ]
        ];
    }

    private function prepareGeocodingResponse(): array
    {
        $localeData = [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
        ];
        return [
            $localeData,
        ];
    }
}
