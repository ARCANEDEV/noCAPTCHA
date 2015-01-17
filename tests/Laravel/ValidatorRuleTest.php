<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\Laravel\Facade as NoCaptcha;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;
use Mockery as m;

class ValidatorRuleTest extends LaravelTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var \Illuminate\Validation\Factory
     */
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
        parent::tearDown();

        unset($this->validator);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanPassCaptchaRule()
    {
        $this->mockRequest([
            'success' => true
        ]);

        $validator = $this->validator->make([
            'g-recaptcha-response' => 'google-recaptcha-response',
        ], [
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    /**
     * @test
     */
    public function testCanFailCaptchaRule()
    {
        $this->mockRequest([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $validator = $this->validator->make([
            // Data
            'g-recaptcha-response'         => 'google-recaptcha-response',
        ],[
            // Rules
            'g-recaptcha-response'         => 'required|captcha',
        ],[
            // Messages
            'g-recaptcha-response.captcha' => 'Your captcha error message',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        $messages = $validator->messages();

        $this->assertTrue($messages->has('g-recaptcha-response'));
        $this->assertEquals(
            'Your captcha error message',
            $messages->first('g-recaptcha-response')
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function mockRequest($returns)
    {
        $request = m::mock('Arcanedev\NoCaptcha\Utilities\Request');
        $request->shouldReceive('send')->andReturn($returns);

        $captcha = $this->app['arcanedev.no-captcha']->setRequestClient($request);
        $this->app['arcanedev.no-captcha'] = $captcha;
    }
}
