<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\WGMetric;

/**
 * @covers \App\Enums\WGMetric
 */
class WGMetricTest extends TestCase
{

    public function successDataProvider(): array
    {
        return [
            [WGMetric::Temp, 'temp'],
            [WGMetric::FeelsLike, 'feels_like'],
            [WGMetric::Pressure, 'pressure'],
            [WGMetric::Humidity, 'humidity'],
            [WGMetric::DewPoint, 'dew_point'],
            [WGMetric::Uvi, 'uvi'],
            [WGMetric::Clouds, 'clouds'],
            [WGMetric::Visibility, 'visibility'],
            [WGMetric::WindSpeed, 'wind_speed'],
            [WGMetric::WindDeg, 'wind_deg'],
        ];
    }

    /**
     * @dataProvider successDataProvider
     */
    public function testValueSuccess(WGMetric $enum, string $value): void
    {
        $this->assertEquals($enum->value, $value);
    }

    public function parametersDataProvider(): array
    {
        return [
            [
                WGMetric::Temp,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Temperature',
                    'external_id' => 'temp'
                ]
            ],
            [
                WGMetric::FeelsLike,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Feels Like Temperature',
                    'external_id' => 'feels_like'
                ]
            ],
            [
                WGMetric::Pressure,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Pressure',
                    'external_id' => 'pressure'
                ]
            ],
            [
                WGMetric::Humidity,
                [
                    'type' => 'float',
                    'name' => 'Humidity',
                    'external_id' => 'humidity',
                    'accumulator' => 'last',
                    'negative_ratio' => false
                ]
            ],
            [
                WGMetric::DewPoint,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Dew Point',
                    'external_id' => 'dew_point'
                ]
            ],
            [
                WGMetric::Uvi,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'UV index',
                    'external_id' => 'uvi'
                ]
            ],
            [
                WGMetric::Clouds,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Cloudiness',
                    'external_id' => 'clouds'
                ]
            ],
            [
                WGMetric::Visibility,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Average visibility',
                    'external_id' => 'visibility'
                ]
            ],
            [
                WGMetric::WindSpeed,
                [
                    'type' => 'float',
                    'accumulator' => 'average',
                    'negative_ratio' => true,
                    'name' => 'Wind speed',
                    'external_id' => 'wind_speed'
                ]
            ],
            [
                WGMetric::WindDeg,
                [
                    'type' => 'float',
                    'name' => 'Wind direction',
                    'external_id' => 'wind_deg',
                    'accumulator' => 'last',
                    'negative_ratio' => false
                ]
            ],
        ];
    }

    /**
     * @dataProvider parametersDataProvider
     */
    public function testParametersSuccess(WGMetric $enum, array $parameters): void
    {
        $this->assertEquals($enum->parameters(), $parameters);
    }
}
