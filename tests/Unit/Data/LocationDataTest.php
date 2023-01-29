<?php

namespace Tests\Unit\Data;

use App\Data\LocationData;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Tests\TestCase;

/**
 * @covers \App\Data\LocationData
 */
class LocationDataTest extends TestCase
{
    use WithFaker;

    public function testSuccess()
    {
        $locationArray = [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
        ];
        $locationData = LocationData::from($locationArray);

        $this->assertEquals($locationArray['name'], $locationData->name);
        $this->assertEquals($locationArray['lat'], $locationData->lat);
        $this->assertEquals($locationArray['lon'], $locationData->lon);
        $this->assertEquals($locationArray['country'], $locationData->country);
        $this->assertEquals($locationArray['state'], $locationData->state);
        $this->assertEquals(implode(',', [$locationArray['name'], $locationArray['state'], $locationArray['country']]),
            $locationData->label());
    }

    public function testOverDataSuccess()
    {
        $locationArray = [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
            'county' => $this->faker->country,
        ];
        $locationData = LocationData::from($locationArray);

        $this->assertEquals($locationArray['name'], $locationData->name);
        $this->assertEquals($locationArray['lat'], $locationData->lat);
        $this->assertEquals($locationArray['lon'], $locationData->lon);
        $this->assertEquals($locationArray['country'], $locationData->country);
        $this->assertEquals($locationArray['state'], $locationData->state);
        $this->assertEquals(implode(',', [$locationArray['name'], $locationArray['state'], $locationArray['country']]),
            $locationData->label());

    }

    public function testLackOfNameException()
    {
        $locationArray = [
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
        ];
        $this->expectException(CannotCreateData::class);
        LocationData::from($locationArray);
    }

    public function testLackOfLatException()
    {
        $locationArray = [
            'name' => $this->faker->city,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
        ];
        $this->expectException(CannotCreateData::class);
        LocationData::from($locationArray);
    }

    public function testLackOfLonException()
    {
        $locationArray = [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
        ];
        $this->expectException(CannotCreateData::class);
        LocationData::from($locationArray);
    }

    public function testLackOfCountryException()
    {
        $locationArray = [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'state' => $this->faker->country,
        ];
        $this->expectException(CannotCreateData::class);
        LocationData::from($locationArray);
    }

    public function testEmptyNameException()
    {
        $locationArray = [
            'name' => null,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->country,
        ];
        $this->expectException(\TypeError::class);
        LocationData::from($locationArray);
    }

}
