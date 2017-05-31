<?php

namespace Flipbox\Http\Stream\Tests\Exceptions;

use Flipbox\Http\Stream\Exceptions\InvalidStreamException;

class InvalidStreamExceptionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function nameMatchesTest()
    {
        $exception = new InvalidStreamException();
        $this->assertEquals('Invalid Stream Exception', $exception->getName());

    }
}