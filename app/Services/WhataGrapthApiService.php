<?php

namespace App\Services;

use App\Data\WeatherData;
use App\Enums\WGMetric;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhataGrapthApiService
{
    public function initMetrics(): void
    {
        $existingMetricsResponse = $this->recursiveRequest('integration-metrics');

        $existingMetrics = [];
        foreach (data_get($existingMetricsResponse, 'data', []) as $metric) {
            $existingMetrics[$metric['external_id']] = $metric;
        }
        foreach (WGMetric::cases() as $metric) {
            if($existingMetric = $existingMetrics[$metric->value] ?? false) {
                $this->send('integration-metrics/' . $existingMetric['id'], 'PUT', $metric->parameters());
            } else {
                $this->send('integration-metrics', 'POST', $metric->parameters());
            }
        }
    }

    public function pushData(WeatherData $weatherData): void
    {
        $this->checkLocationDimension();

        $data = [
           'location' => $weatherData->location->label(),
           'date' => $weatherData->dt->format('Y-m-d'),
        ];

        foreach (WGMetric::cases() as $metric) {
            $data[$metric->value] = $weatherData->{$metric->value};
        }

        $this->send('integration-source-data', 'POST', ['data' => [$data]]);
    }

    private function checkLocationDimension(): void
    {
        $dimensionsResponse = $this->send(endpoint: 'integration-dimensions', params: ['external_id' => 'location']);
        if(!empty(data_get($dimensionsResponse, 'data'))) {
            return;
        }
        $this->send('integration-dimensions', 'POST', [
            'name' => 'Location',
            'external_id' => 'location',
            'type' => 'string',
        ]);
    }

    private function recursiveRequest(string $endpoint, array $parameters = []): array
    {
        $response = $this->send($endpoint);

        $data = data_get($response, 'data', []);
        if($nextPageUrl = data_get($response, 'links.next')) {
            Log::debug('Next url: ' . $nextPageUrl);
            $data = array_merge($data,
                data_get($this->recursiveRequest($nextPageUrl), 'data', [])
            );
        }
        return [
            'data' => $data,
        ];
    }

    private function send(string $endpoint, string $method = 'GET', array $params = []): array
    {
        $method = strtoupper($method);

        if(!preg_match('/^https?:\/\//', $endpoint)) {
            $url = config('services.whatagraph.base_url') . $endpoint;
        } else {
            $url = $endpoint;
        }

        if($method == 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
        } elseif(in_array($method, ['POST', 'PUT'])) {
            $options = ['json' => $params];
        }

        $response = Http::withToken(config('services.whatagraph.token'))->send($method, $url, $options ?? []);
        $response->throw();

        return $response->json() ?? [];
    }
}
