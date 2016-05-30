<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\NoCaptcha;
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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Illuminate\Validation\Factory */
    private $validator;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
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
            NoCaptcha::CAPTCHA_NAME => 'required|captcha',
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
            NoCaptcha::CAPTCHA_NAME => 'required|captcha',
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

    /** @test */
    public function it_can_fail_captcha_rule_with_messages()
    {
        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            NoCaptcha::CAPTCHA_NAME => 'google-recaptcha-response',
        ],[
            NoCaptcha::CAPTCHA_NAME => 'required|captcha',
        ],[
            'g-recaptcha-response.captcha' => 'Your captcha error message',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        $errors = $validator->messages();

        $this->assertTrue($errors->has(NoCaptcha::CAPTCHA_NAME));
        $this->assertEquals(
            'Your captcha error message',
            $errors->first(NoCaptcha::CAPTCHA_NAME)
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function mockRequest(array $returns)
    {
        $request = $this->prophesize(Request::class);
        $request->send(Argument::type('string'))
            ->willReturn($returns);

        $captcha = $this->app['arcanedev.no-captcha']
            ->setRequestClient($request->reveal());

        $this->app['arcanedev.no-captcha'] = $captcha;
    }
}
