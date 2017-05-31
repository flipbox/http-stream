<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/http-stream/blob/master/LICENSE
 * @link       https://github.com/flipbox/http-stream
 */

namespace Flipbox\Http\Stream;

use Countable;
use IteratorAggregate;
use Psr\Http\Message\StreamInterface;
use Traversable;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 2.0.0
 */
class IteratorStream implements StreamInterface
{
    /**
     * @var \Traversable|\Iterator
     */
    private $iterator;

    /**
     * Current position in iterator
     *
     * @var int
     */
    private $position = 0;

    /**
     * Construct a stream instance using an iterator.
     *
     * IteratorStream constructor.
     * @param Traversable $iterator
     * @param array $options
     */
    public function __construct(Traversable $iterator, $options = [])
    {
        $this->iterator = $iterator;
    }

    /**
     * @return \Iterator
     */
    private function getIterator()
    {
        if ($this->iterator instanceof IteratorAggregate) {
            return $this->iterator->getIterator();
        }
        return $this->iterator;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->getIterator()->rewind();
        return $this->getContents();
    }

    /**
     * @return void
     */
    public function close()
    {
    }

    /**
     * @return null|Traversable
     */
    public function detach()
    {
        $iterator = $this->iterator;
        $this->iterator = null;
        return $iterator;
    }

    /**
     * @return int|null
     */
    public function getSize()
    {
        $iterator = $this->getIterator();
        if ($iterator instanceof Countable) {
            return count($iterator);
        }
        return null;
    }

    /**
     * @return int
     */
    public function tell()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function eof()
    {
        $iterator = $this->getIterator();
        if ($iterator instanceof Countable) {
            return ($this->position === count($iterator));
        }
        return (!$iterator->valid());
    }

    /**
     * @return bool
     */
    public function isSeekable()
    {
        return true;
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return bool
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!is_int($offset) && !is_numeric($offset)) {
            return false;
        }
        $offset = (int)$offset;
        if ($offset < 0) {
            return false;
        }
        $iterator = $this->getIterator();
        $key = $iterator->key();
        if (!is_int($key) && !is_numeric($key)) {
            $key = 0;
            $iterator->rewind();
        }
        if ($key >= $offset) {
            $key = 0;
            $iterator->rewind();
        }
        while ($iterator->valid() && $key < $offset) {
            $iterator->next();
            ++$key;
        }
        $this->position = $key;
        return true;
    }

    /**
     * @return bool
     */
    public function rewind()
    {
        $this->getIterator()->rewind();
        $this->position = 0;
        return true;
    }

    /**
     * @return bool
     */
    public function isWritable()
    {
        return false;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function write($string)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isReadable()
    {
        return true;
    }

    /**
     * @param int $length
     * @return string
     */
    public function read($length)
    {
        $index = 0;
        $contents = '';
        $iterator = $this->getIterator();
        while ($iterator->valid() && $index < $length) {
            $contents .= $iterator->current();
            $iterator->next();
            ++$this->position;
            ++$index;
        }
        return $contents;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        $contents = '';
        $iterator = $this->getIterator();
        while ($iterator->valid()) {
            $contents .= $iterator->current();
            $iterator->next();
            ++$this->position;
        }
        return $contents;
    }

    /**
     * @param null $key
     * @return array|null
     */
    public function getMetadata($key = null)
    {
        return ($key === null) ? array() : null;
    }
}
