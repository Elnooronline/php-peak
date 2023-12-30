<?php

namespace Elnooronline\Peak\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;

class Map implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Array of initial data.
     *
     * @var array
     */
    private array $data = [];

    /**
     * Map constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get an element from an array using "dot" notation.
     *
     * @param string|null $key
     * @param $default
     * @return array|mixed|null
     */
    public function get(string $key = null, $default = null)
    {
        if (is_null($key))
            return $this->all();

        if ($this->has($key))
            return $this->data[$key];

        if (! str_contains($key, '.'))
            return $this->data[$key] ?? $default;

        $data = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $data)) {
                $data = $data[$segment];
            } else {
                return $default;
            }
        }

        return $data;
    }

    /**
     * Determine if data has specific key.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * Set value to a specific key or append a value.
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value = null)
    {
        if (is_null($value))
            return $this->push($key);

        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Append value at the end of the array.
     *
     * @param $value
     * @return $this
     */
    public function push($value)
    {
        $this->data[] = $value;

        return $this;
    }

    /**
     * Get last element in array.
     *
     * @param $default
     * @return false|mixed|null
     */
    public function last($default = null)
    {
        return $this->isEmpty() ? $default : end($this->data);
    }

    /**
     * Remove specific key from the array.
     *
     * @param string $key
     * @return $this
     */
    public function remove(string $key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * Merge the given values to the array.
     *
     * @param ...$values
     * @return $this
     */
    public function merge(...$values)
    {
        $this->data = array_replace_recursive(
            array_merge_recursive(
                $this->data,
                ...$values
            )
        );

        return $this;
    }

    /**
     * Count elements of array.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() <= 0;
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @param $offset
     * @return array|mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset, null);
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param $name
     * @return array|mixed|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }
}