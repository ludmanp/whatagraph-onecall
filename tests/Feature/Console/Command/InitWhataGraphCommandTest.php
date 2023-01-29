<?php

namespace Tests\Feature\Console\Command;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Console\Commands\InitWhataGraphCommand
 */
class InitWhataGraphCommandTest extends TestCase
{
    const URL = 'https://api.whatagraph.com/v1/integration-metrics';

    public function testSuccess()
    {
        Http::fake([
            self::URL . '*' => Http::response([]),
        ]);
        $this->artisan(' whatagraph:init')->assertSuccessful();
    }

    public function testException()
    {
        Http::fake([
            self::URL . '*' => Http::response([], 401),
        ]);
        $this->expectException(RequestException::class);

        $this->artisan(' whatagraph:init');
    }
}
