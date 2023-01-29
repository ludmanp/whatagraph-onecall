<?php

namespace App\Data;

use App\Data\Casts\TimestampCast;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class WeatherData extends Data
{
    public function __construct(
        #[WithCast(TimestampCast::class)]
        public CarbonImmutable $dt,
        #[WithCast(TimestampCast::class)]
        public CarbonImmutable $sunrise,
        #[WithCast(TimestampCast::class)]
        public CarbonImmutable $sunset,
        public float $temp,
        public float $feels_like,
        public int $pressure,
        public int $humidity,
        public float $dew_point,
        public float $uvi,
        public float $clouds,
        public int $visibility,
        public float $wind_speed,
        public int $wind_deg,
        public LocationData $location,
    )
    {}

}
