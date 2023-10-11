<?php

namespace Illuminate\Support;

use BadFunctionCallException;

/**
 * @method bool accessible()
 * @method self add(string|int|float $key, mixed $value)
 * @method self collapse()
 * @method self crossJoin(iterable ...$arrays)
 * @method self divide()
 * @method self dot(string $prepend = '')
 * @method self undot()
 * @method self except(array|string|int|float $keys)
 * @method bool exists(string|int $key)
 * @method self first(callable $callback = null, $default = null)
 * @method self last(callable $callback = null, $default = null)
 * @method self flatten(int $depth = INF)
 * @method self forget(array|string|int|float $keys)
 * @method mixed|self get(string|int $key, $default = null)
 * @method bool has(string|array $keys)
 * @method bool hasAny(array|string|int|float $keys)
 * @method bool isAssoc()
 * @method bool isList()
 * @method string join(string $glue, string $finalGlue = '')
 * @method self keyBy(callable|array|string $keyBy)
 * @method self prependKeysWith(string $prependWith)
 * @method self only(array|string $keys)
 * @method self pluck(string|array|int|null $value, string|array|null $key = null)
 * @method self explodePluckParameters(string|array|null $key = null)
 * @method self map(callable $callback)
 * @method self mapWithKeys(callable $callback)
 * @method self prepend(mixed $value, mixed $key = null)
 * @method self pull(string|int $key, mixed $default = null)
 * @method string query()
 * @method self random(int|null $number = null, bool $preserveKeys = false)
 * @method self set(string|int|null $key, mixed $value)
 * @method self shuffle(int|null $seed = null)
 * @method self sort(callable $callback = null)
 * @method self sortDesc(callable $callback = null)
 * @method self sortRecursive(int $options = SORT_REGULAR, bool $descending = false)
 * @method self sortRecursiveDesc(int $options = SORT_REGULAR)
 * @method self toCssClasses()
 * @method self toCssStyles()
 * @method self where(callable $callback)
 * @method self whereNotNull()
 * @method self wrap()
 */
class Arrayable implements \Illuminate\Contracts\Support\Arrayable, \ArrayAccess
{
    /**
     * Create a new arrayable instance.
     *
     * @param $array
     */
    public function __construct(protected $array)
    {
        //
    }

    /**
     * When a method is called on the arrayable instance, we'll
     * call the method on the Arr class and return a new Arrayable
     * instance, or the resulting value.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed|self
     */
    public function __call(string $name, array $arguments)
    {
        if (!method_exists(Arr::class, $name)) {
            throw new BadFunctionCallException("$name function does not exist");
        }

        $response = Arr::$name($this->array, ...$arguments);

        if (is_array($response)) {
            return new self($response);
        }

        return $response;
    }

    public function toArray(): array
    {
        return $this->array;
    }

    public function toJson(int $flags = 0, int $depth = 512): string
    {
        return json_encode($this->array, $flags, $depth);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param $offset
     * @return array
     */
    public function offsetGet($offset): mixed
    {
        return $this->array[$offset];
    }

    /**
     * Set the item at a given offset.
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->array[$offset]);
    }
}
