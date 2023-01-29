<?php

namespace App\Data\Casts;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class TimestampCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return CarbonImmutable::createFromTimestamp($value);
    }
}
