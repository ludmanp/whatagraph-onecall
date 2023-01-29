<?php

namespace Tests\Unit\Services\WhataGrapthApiServiceTest;

use App\Enums\WGMetric;
use App\Services\WhataGrapthApiService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Services\WhataGrapthApiService
 * @covers \App\Services\WhataGrapthApiService::initMetrics
 * @covers \App\Services\WhataGrapthApiService::checkLocationDimension
 */
class InitMetricsTest extends TestCase
{
    use WithFaker;
    const URL = 'https://api.whatagraph.com/v1/integration-metrics';

    public function testSuccess()
    {
        $id = $this->faker->randomNumber(4);

        Http::fake(function (Request $request) use($id) {
            $url = $request->url();
            $method = $request->method();
            if ($url == self::URL && $method == 'GET') {
                return Http::response([
                    'data' => [
                        [
                            'id' => $id,
                            'external_id' => 'clouds',
                            'name' => 'Cloudiness',
                            'type' => 'float',
                            'negative_ratio' => true,
                            'options' => [
                                'accumulator' => 'average'
                            ]
                        ]]
                ]);
            } elseif(strstr($url, self::URL) && in_array($method, ['POST', 'PUT'])) {
                return Http::response([], 200);
            }

            throw new \Exception('Unknown request');
        });

        (new WhataGrapthApiService())->initMetrics();

        Http::assertSent(function (Request $request) {
            return $request->method() == 'GET' &&
                $request->url() == self::URL;
        });
        Http::assertSent(function (Request $request) use($id) {
            return $request->method() == 'PUT' &&
                $request->url() == self::URL . '/' . $id;
        });
        Http::assertSent(function (Request $request) {
            return $request->method() == 'POST' &&
                $request->url() == self::URL;
        });
        Http::assertSentCount(count(WGMetric::cases())+1);
    }

    public function testException()
    {
        Http::fake([
            self::URL . '*' => Http::response([], 401),
        ]);
        $this->expectException(RequestException::class);

        (new WhataGrapthApiService())->initMetrics();
    }
}
