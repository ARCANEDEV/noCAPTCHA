<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\NoCaptcha;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;
use Arcanedev\NoCaptcha\Utilities\Request;
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

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->app['validator'];
    }

    public function tearDown()
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
            NoCaptcha::CAPTCHA_NAME => 'google-recaptcha-response',
        ], [
            NoCaptcha::CAPTCHA_NAME => ['required', new CaptchaRule],
        ]);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_can_fails_captcha_rule()
    {
        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            NoCaptcha::CAPTCHA_NAME => 'google-recaptcha-response',
        ],[
            NoCaptcha::CAPTCHA_NAME => ['required', new CaptchaRule],
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        $errors = $validator->messages();

        $this->assertTrue($errors->has(NoCaptcha::CAPTCHA_NAME));
        $this->assertEquals(
            'validation.captcha',
            $errors->first(NoCaptcha::CAPTCHA_NAME)
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    private function mockRequest(array $returns)
    {
        $request = $this->prophesize(Request::class);
        $request->send(Argument::type('string'))
            ->willReturn($returns);

        $captcha = $this->app->make(\Arcanedev\NoCaptcha\Contracts\NoCaptcha::class)
            ->setRequestClient($request->reveal());

        $this->app[\Arcanedev\NoCaptcha\Contracts\NoCaptcha::class] = $captcha;
    }
}
