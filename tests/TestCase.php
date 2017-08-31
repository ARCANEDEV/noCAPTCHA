<?php namespace Arcanedev\NoCaptcha\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\NoCaptcha\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
