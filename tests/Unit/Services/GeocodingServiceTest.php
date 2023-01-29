<?php

namespace Tests\Unit\Services;

use App\Services\OpenWeatherApi\GeocodingService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Services\OpenWeatherApi\GeocodingService
 */
class GeocodingServiceTest extends TestCase
{
    use WithFaker;

    const URL_GEOCODING = 'https://api.openweathermap.org/geo/1.0/direct*';

    public function testGetLocationSuccess()
    {
        $response = $this->prepareResponse();
        Http::fake([
            self::URL_GEOCODING => Http::response($response),
        ]);

        $localeData = (new GeocodingService)->getLocation('Riga');

        $this->assertEquals($response[0]['name'], $localeData->name);
        $this->assertEquals($response[0]['lat'], $localeData->lat);
        $this->assertEquals($response[0]['lon'], $localeData->lon);
        $this->assertEquals($response[0]['country'], $localeData->country);
    }

    public function testGetLocationException()
    {
        $response = $this->prepareResponse();
        Http::fake([
            self::URL_GEOCODING => Http::response($response, 401),
        ]);
        $this->expectException(RequestException::class);

        (new GeocodingService)->getLocation('Riga');
    }

    private function prepareResponse(): array
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
