<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

abstract class OpenWeatherApiService
{
    protected function send(string $endpoint, array $params = []): array
    {
        $url = $endpoint;

        if(!empty($params)) {
            $url .= $this->prepareParameters($params);
        }

        $response = Http::send('GET', $url, $options ?? []);
        $response->throw();

        return $response->json() ?? [];
    }

    private function prepareParameters(array $params = []): string
    {
        $params = array_merge($params, ['appid' => config('services.openweathermap.key')]);
        return '?' . http_build_query($params);
    }
}
