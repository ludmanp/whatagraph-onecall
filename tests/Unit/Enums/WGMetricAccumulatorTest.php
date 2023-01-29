<?php

namespace Tests\Unit\Enums;

use App\Enums\WGMetricAccumulator;
use Tests\TestCase;

/**
 * @covers \App\Enums\WGMetricAccumulator
 */
class WGMetricAccumulatorTest extends TestCase
{
    public function successDataProvider(): array
    {
        return [
            [WGMetricAccumulator::Last, 'last'],
            [WGMetricAccumulator::Sum, 'sum'],
            [WGMetricAccumulator::Average, 'average'],
        ];
    }

    /**
     * @dataProvider successDataProvider
     */
    public function testValueSuccess(WGMetricAccumulator $enum, string $value): void
    {
        $this->assertEquals($enum->value, $value);
    }
}
