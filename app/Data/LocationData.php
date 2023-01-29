<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class LocationData extends Data
{
    public function __construct(
        public string $name,
        public float $lat,
        public float $lon,
        public string $country,
        public ?string $state,
    )
    {}

    public function label()
    {
        return implode(',', array_filter([$this->name, $this->state, $this->country]));
    }
}
