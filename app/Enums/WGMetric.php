<?php

namespace App\Enums;

enum WGMetric: string
{
        case Temp = 'temp';
        case FeelsLike = 'feels_like';
        case Pressure = 'pressure';
        case Humidity = 'humidity';
        case DewPoint = 'dew_point';
        case Uvi = 'uvi';
        case Clouds = 'clouds';
        case Visibility = 'visibility';
        case WindSpeed = 'wind_speed';
        case WindDeg = 'wind_deg';

        public function parameters(): array
        {
            $default = [
                'type' => 'float',
                'accumulator' => WGMetricAccumulator::Average->value,
                'negative_ratio' => true
            ];
            return match ($this) {
                static::Temp => array_merge($default, ['name' => 'Temperature', 'external_id' => $this->value]),
                static::FeelsLike => array_merge($default, ['name' => 'Feels Like Temperature', 'external_id' => $this->value]),
                static::Pressure => array_merge($default, ['name' => 'Pressure', 'external_id' => $this->value]),
                static::Humidity => array_merge($default, ['name' => 'Humidity', 'external_id' => $this->value, 'accumulator' => WGMetricAccumulator::Last->value, 'negative_ratio' => false]),
                static::DewPoint => array_merge($default, ['name' => 'Dew Point', 'external_id' => $this->value]),
                static::Uvi => array_merge($default, ['name' => 'UV index', 'external_id' => $this->value]),
                static::Clouds => array_merge($default, ['name' => 'Cloudiness', 'external_id' => $this->value]),
                static::Visibility => array_merge($default, ['name' => 'Average visibility', 'external_id' => $this->value]),
                static::WindSpeed => array_merge($default, ['name' => 'Wind speed', 'external_id' => $this->value]),
                static::WindDeg => array_merge($default, ['name' => 'Wind direction', 'external_id' => $this->value, 'accumulator' => WGMetricAccumulator::Last->value, 'negative_ratio' => false]),
            };
        }
}
