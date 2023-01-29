<?php

namespace App\Enums;

enum WGMetricAccumulator: string
{
    case Sum = 'sum';
    case Average = 'average';
    case Last = 'last';
}
