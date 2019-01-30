<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\ResponseV2;

/**
 * Class     ResponseV2Test
 *
 * @package  Arcanedev\NoCaptcha\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResponseV2Test extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $response = new ResponseV2(true);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_be_instantiated_from_json()
    {
        $response = ResponseV2::fromJson('{"success": true}');

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $response = ResponseV2::fromArray(['success' => true]);

        static::assertInstanceOf(\JsonSerializable::class, $response);
        static::assertInstanceOf(\Illuminate\Contracts\Support\Jsonable::class, $response);

        static::assertSame(
            '{"success":true,"hostname":null,"challenge_ts":null,"apk_package_name":null,"error-codes":[]}',
            $response->toJson()
        );
    }

    /** @test */
    public function it_can_be_instantiated_from_array()
    {
        $response = ResponseV2::fromArray(['success' => true]);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_get_response_data()
    {
        $response = new ResponseV2(
            false,
            ['challenge-timeout'],
            'localhost',
            '2019-01-01T00:00:00Z',
            null,
            0.5,
            'action-name'
        );

        static::assertFalse($response->isSuccess());
        static::assertSame(['challenge-timeout'], $response->getErrorCodes());
        static::assertSame('localhost', $response->getHostname());
        static::assertSame('2019-01-01T00:00:00Z', $response->getChallengeTs());
        static::assertSame(null, $response->getApkPackageName());

        static::assertEquals([
            'success'          => false,
            'hostname'         => 'localhost',
            'challenge_ts'     => '2019-01-01T00:00:00Z',
            'apk_package_name' => null,
            'error-codes'      => ['challenge-timeout'],
        ], $response->toArray());
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $response = new ResponseV2(
            false,
            ['challenge-timeout'],
            'localhost',
            '2019-01-01T00:00:00Z',
            null
        );

        static::assertInstanceOf(\Illuminate\Contracts\Support\Arrayable::class, $response);
        static::assertEquals([
            'success'          => false,
            'hostname'         => 'localhost',
            'challenge_ts'     => '2019-01-01T00:00:00Z',
            'apk_package_name' => null,
            'error-codes'      => ['challenge-timeout'],
        ], $response->toArray());
    }

    /** @test */
    public function it_can_check_the_hostname()
    {
        $response = ResponseV2::fromArray([
            'success'  => true,
            'hostname' => 'localhost',
        ]);

        static::assertTrue($response->isHostname('localhost'));

        $response = ResponseV2::fromArray([
            'success'  => true,
            'hostname' => 'example.com',
        ]);

        static::assertFalse($response->isHostname('localhost'));
    }
}
