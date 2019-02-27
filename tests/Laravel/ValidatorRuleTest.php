<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\NoCaptchaV3;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;
use Arcanedev\NoCaptcha\Utilities\Request;
use Arcanedev\NoCaptcha\Utilities\ResponseV3;
use Prophecy\Argument;

/**
 * Class     ValidatorRuleTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValidatorRuleTest extends LaravelTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Illuminate\Validation\Factory */
    private $validator;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->app['validator'];
    }

    public function tearDown(): void
    {
        unset($this->validator);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_passes_captcha_rule()
    {
        $this->mockRequest([
            'success' => true
        ]);

        $validator = $this->validator->make([
            NoCaptchaV3::CAPTCHA_NAME => 'google-recaptcha-response',
        ], [
            NoCaptchaV3::CAPTCHA_NAME => ['required', new CaptchaRule],
        ]);

        static::assertTrue($validator->passes());
        static::assertFalse($validator->fails());
    }

    /** @test */
    public function it_can_fails_captcha_rule()
    {
        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            NoCaptchaV3::CAPTCHA_NAME => 'google-recaptcha-response',
        ],[
            NoCaptchaV3::CAPTCHA_NAME => ['required', new CaptchaRule],
        ]);

        static::assertFalse($validator->passes());
        static::assertTrue($validator->fails());

        $errors = $validator->messages();

        static::assertTrue($errors->has(NoCaptchaV3::CAPTCHA_NAME));
        static::assertEquals(
            'validation.captcha',
            $errors->first(NoCaptchaV3::CAPTCHA_NAME)
        );
    }

    /** @test */
    public function it_can_skip_ips()
    {
        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            NoCaptchaV3::CAPTCHA_NAME => 'google-recaptcha-response',
        ],[
            NoCaptchaV3::CAPTCHA_NAME => ['required', (new CaptchaRule)->skipIps('127.0.0.1')],
        ]);

        static::assertTrue($validator->passes());
    }

    /** @test */
    public function it_can_skip_ips_via_config_file()
    {
        $this->app['config']->set('no-captcha.skip-ips', ['127.0.0.1']);

        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            NoCaptchaV3::CAPTCHA_NAME => 'google-recaptcha-response',
        ],[
            NoCaptchaV3::CAPTCHA_NAME => ['required', new CaptchaRule],
        ]);

        static::assertTrue($validator->passes());
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    private function mockRequest(array $response)
    {
        $request = tap($this->prophesize(Request::class), function ($request) use ($response) {
            $request->send(Argument::type('string'))->willReturn(
                json_encode($response)
            );
        })->reveal();

        $this->app[\Arcanedev\NoCaptcha\Contracts\NoCaptcha::class] = $this->app
            ->make(\Arcanedev\NoCaptcha\Contracts\NoCaptcha::class)
            ->setRequestClient($request);
    }
}
