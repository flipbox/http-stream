<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/http/blob/master/LICENSE
 * @link       https://github.com/flipbox/http
 */

namespace Flipbox\Http\Stream\Exceptions;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class InvalidStreamException extends \Exception
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Invalid Stream Exception';
    }
}
