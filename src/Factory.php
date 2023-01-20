<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/http-stream/blob/master/LICENSE
 * @link       https://github.com/flipbox/http-stream
 */

namespace Flipbox\Http\Stream;

use Flipbox\Http\Stream\Exceptions\InvalidStreamException;
use Psr\Http\Message\StreamInterface;
use Laminas\Diactoros\Stream;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class Factory
{
    /**
     * Create a new stream based on the input type.
     *
     * @param string $resource
     * @return StreamInterface
     * @throws InvalidStreamException
     */
    public static function create($resource = ''): StreamInterface
    {
        switch (gettype($resource)) {
            case 'string':
                $stream = fopen('php://temp', 'r+');
                if ($resource !== '') {
                    fwrite($stream, $resource);
                    fseek($stream, 0);
                }
                return new Stream($stream);
            case 'resource':
                return new Stream($resource);
            case 'object':
                if ($resource instanceof StreamInterface) {
                    return $resource;
                } elseif ($resource instanceof \Traversable) {
                    return new IteratorStream($resource);
                } elseif (method_exists($resource, '__toString')) {
                    return static::create((string)$resource);
                }
                break;
            case 'NULL':
                return new Stream('php://temp', 'r+');
        }

        throw new InvalidStreamException(
            'Invalid resource type: ' . gettype($resource)
        );
    }
}
