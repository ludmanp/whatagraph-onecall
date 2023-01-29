<?php

namespace Tests\Feature\Console\Command;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Console\Commands\OneCallToWhataGraphCommand
 */
class OneCallToWhataGraphCommandTest extends TestCase
{
    use WithFaker;

    const URL_DIMENSIONS = 'https://api.whatagraph.com/v1/integration-dimensions';
    const URL_DATA = 'https://api.whatagraph.com/v1/integration-source-data';

    public function testSuccess()
    {
        Http::fake([
            self::URL_DIMENSIONS . '*' => Http::response([]),
            self::URL_DATA . '*' => Http::response([]),
        ]);

        $this->artisan('whatagraph:one-call Riga')->assertSuccessful();

    }

    public function testException()
    {
        Http::fake([
            self::URL_DIMENSIONS . '*' => Http::response([], 401),
            self::URL_DATA . '*' => Http::response([]),
        ]);
        $this->expectException(RequestException::class);

        $this->artisan('whatagraph:one-call Riga');

    }
}
