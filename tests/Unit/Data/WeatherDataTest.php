<?php

namespace Tests\Unit\Data;

use App\Data\WeatherData;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Tests\TestCase;
use Tests\Traits\WithWeaterData;

/**
 * @covers \App\Data\WeatherData
 */
class WeatherDataTest extends TestCase
{
    use WithFaker;
    use WithWeaterData;

    public function testSuccess()
    {
        $weatherArray = $this->prepareWeatherArray();

        $weatherData = WeatherData::from($weatherArray);

        $this->assertEquals($weatherArray['dt'], $weatherData->dt->timestamp);
        $this->assertEquals($weatherArray['sunrise'], $weatherData->sunrise->timestamp);
        $this->assertEquals($weatherArray['sunset'], $weatherData->sunset->timestamp);
        $this->assertEquals($weatherArray['temp'], $weatherData->temp);
        $this->assertEquals($weatherArray['feels_like'], $weatherData->feels_like);
        $this->assertEquals($weatherArray['pressure'], $weatherData->pressure);
        $this->assertEquals($weatherArray['humidity'], $weatherData->humidity);
        $this->assertEquals($weatherArray['dew_point'], $weatherData->dew_point);
        $this->assertEquals($weatherArray['uvi'], $weatherData->uvi);
        $this->assertEquals($weatherArray['clouds'], $weatherData->clouds);
        $this->assertEquals($weatherArray['visibility'], $weatherData->visibility);
        $this->assertEquals($weatherArray['wind_speed'], $weatherData->wind_speed);
        $this->assertEquals($weatherArray['wind_deg'], $weatherData->wind_deg);
        $this->assertEquals($weatherArray['location']->name, $weatherData->location->name);
        $this->assertEquals($weatherArray['location']->lat, $weatherData->location->lat);
        $this->assertEquals($weatherArray['location']->lon, $weatherData->location->lon);
        $this->assertEquals($weatherArray['location']->country, $weatherData->location->country);
    }

    public function testEmptyDataException()
    {
        $weatherArray = $this->prepareWeatherArray();
        unset($weatherArray['clouds']);

        $this->expectException(CannotCreateData::class);

        $weatherData = WeatherData::from($weatherArray);
    }
}
