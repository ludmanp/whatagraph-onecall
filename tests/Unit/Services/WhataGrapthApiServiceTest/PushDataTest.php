<?php

namespace Tests\Unit\Services\WhataGrapthApiServiceTest;

use App\Services\WhataGrapthApiService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\WithWeaterData;

/**
 * @covers \App\Services\WhataGrapthApiService
 * @covers \App\Services\WhataGrapthApiService::pushData
 */
class PushDataTest extends TestCase
{
    use WithFaker;
    use WithWeaterData;

    const URL_DIMENSIONS = 'https://api.whatagraph.com/v1/integration-dimensions';
    const URL_DATA = 'https://api.whatagraph.com/v1/integration-source-data';

    public function testSuccess()
    {
        $weatherData = $this->prepareWeatherData();

        Http::fake([
            self::URL_DIMENSIONS . '*' => Http::response([]),
            self::URL_DATA . '*' => Http::response([]),
        ]);

        (new WhataGrapthApiService)->pushData($weatherData);

        Http::assertSent(function (Request $request) {
            return $request->method() == 'GET' &&
                strstr($request->url(), self::URL_DIMENSIONS) !== false;
        });
        Http::assertSent(function (Request $request) {
            return $request->method() == 'POST' &&
                strstr($request->url(), self::URL_DIMENSIONS) !== false;
        });
        Http::assertSent(function (Request $request) use($weatherData) {
            $res = false;
            if($request->method() == 'POST' &&
                $request->url() == self::URL_DATA) {
                $res = true;
                $data = data_get($request->data(), 'data.0');
                if(empty($data)) {
                    return false;
                }
                foreach($data as $key => $value) {
                    if($key == 'location') {
                        $res = ($weatherData->location->label() == $value);
                    } elseif ($key == 'date') {
                        $res = ($weatherData->dt->format('Y-m-d') == $value);
                    } else {
                        $res = ($weatherData->$key == $value);
                    }
                    if(!$res) {
                        return false;
                    }
                }
            }
            return $res;
        });
    }

    public function testException()
    {
        $weatherData = $this->prepareWeatherData();

        Http::fake([
            self::URL_DIMENSIONS . '*' => Http::response([]),
            self::URL_DATA . '*' => Http::response([], 400),
        ]);

        $this->expectException(RequestException::class);

        (new WhataGrapthApiService)->pushData($weatherData);
    }

    public function testNotSendDimensionSuccess()
    {
        $weatherData = $this->prepareWeatherData();

        Http::fake([
            self::URL_DIMENSIONS . '*' => Http::response(['data' => ['some data']]),
            self::URL_DATA . '*' => Http::response([]),
        ]);

        (new WhataGrapthApiService)->pushData($weatherData);

        Http::assertSent(function (Request $request) {
            return $request->method() == 'GET' &&
                strstr($request->url(), self::URL_DIMENSIONS) !== false;
        });
        Http::assertNotSent(function (Request $request) {
            return $request->method() == 'POST' &&
                strstr($request->url(), self::URL_DIMENSIONS) !== false;
        });
    }
}
