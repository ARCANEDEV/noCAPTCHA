<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\ResponseV3;

/**
 * Class     ResponseV3Test
 *
 * @package  Arcanedev\NoCaptcha\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResponseV3Test extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $response = new ResponseV3(true);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_be_instantiated_from_json()
    {
        $response = ResponseV3::fromJson('{"success": true}');

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_be_instantiated_with_invalid_json()
    {
        $response = ResponseV3::fromJson('');

        static::assertFalse($response->isSuccess());
        static::assertEquals([ResponseV3::E_INVALID_JSON], $response->getErrorCodes());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $response = ResponseV3::fromArray(['success' => true]);

        static::assertInstanceOf(\JsonSerializable::class, $response);
        static::assertInstanceOf(\Illuminate\Contracts\Support\Jsonable::class, $response);

        static::assertSame(
            '{"success":true,"hostname":null,"challenge_ts":null,"apk_package_name":null,"score":null,"action":null,"error-codes":[]}',
            $response->toJson()
        );
    }

    /** @test */
    public function it_can_be_instantiated_from_array()
    {
        $response = ResponseV3::fromArray(['success' => true]);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_get_response_data()
    {
        $response = new ResponseV3(
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
        static::assertSame(0.5, $response->getScore());
        static::assertSame('action-name', $response->getAction());

        static::assertEquals([
            'success'          => false,
            'hostname'         => 'localhost',
            'challenge_ts'     => '2019-01-01T00:00:00Z',
            'apk_package_name' => null,
            'score'            => 0.5,
            'action'           => 'action-name',
            'error-codes'      => ['challenge-timeout'],
        ], $response->toArray());
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $response = new ResponseV3(
            false,
            ['challenge-timeout'],
            'localhost',
            '2019-01-01T00:00:00Z',
            null,
            0.5,
            'action-name'
        );

        static::assertInstanceOf(\Illuminate\Contracts\Support\Arrayable::class, $response);
        static::assertEquals([
            'success'          => false,
            'hostname'         => 'localhost',
            'challenge_ts'     => '2019-01-01T00:00:00Z',
            'apk_package_name' => null,
            'score'            => 0.5,
            'action'           => 'action-name',
            'error-codes'      => ['challenge-timeout'],
        ], $response->toArray());
    }

    /** @test */
    public function it_can_check_the_score()
    {
        $response = ResponseV3::fromArray([
            'success' => true,
            'score'   => 0.5,
        ]);

        static::assertTrue($response->isScore(.5));

        $response = ResponseV3::fromArray([
            'success' => true,
            'score'   => 1,
        ]);

        static::assertTrue($response->isScore(.8));

        $response = ResponseV3::fromArray([
            'success' => true,
            'score'   => 0.4,
        ]);

        static::assertFalse($response->isScore(.5));
    }

    /** @test */
    public function it_can_check_the_hostname()
    {
        $response = ResponseV3::fromArray([
            'success'  => true,
            'hostname' => 'localhost',
        ]);

        static::assertTrue($response->isHostname('localhost'));

        $response = ResponseV3::fromArray([
            'success'  => true,
            'hostname' => 'example.com',
        ]);

        static::assertFalse($response->isHostname('localhost'));
    }

    /** @test */
    public function it_can_check_the_action_name()
    {
        $response = ResponseV3::fromArray([
            'success' => true,
            'action'  => 'login',
        ]);

        static::assertTrue($response->isAction('login'));

        $response = ResponseV3::fromArray([
            'success' => true,
            'action'  => 'contact',
        ]);

        static::assertFalse($response->isAction('login'));
    }
}
