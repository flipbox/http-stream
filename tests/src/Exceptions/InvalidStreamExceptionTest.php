<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/http-stream/blob/master/LICENSE
 * @link       https://github.com/flipbox/http-stream
 */

namespace Flipbox\Http\Stream\Tests\Exceptions;

use Flipbox\Http\Stream\Exceptions\InvalidStreamException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class InvalidStreamExceptionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function nameMatchesTest()
    {
        $exception = new InvalidStreamException();
        $this->assertEquals('Invalid Stream Exception', $exception->getName());

    }
}