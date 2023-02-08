<?php

declare(strict_types=1);

namespace theodorejb\ArrayUtils;

use OutOfBoundsException;
use UnexpectedValueException;

/**
 * Returns true if all the needles are in the haystack
 */
function contains_all(array $needles, array $haystack): bool
{
    // return false if any of the needles aren't in the haystack
    /** @var mixed $needle */
    foreach ($needles as $needle) {
        if (!in_array($needle, $haystack, true)) {
            return false;
        }
    }

    return true;
}

/**
 * Returns true if the two arrays contain exactly the same values
 * (not necessarily in the same order)
 */
function contains_same(array $a, array $b): bool
{
    return contains_all($a, $b) && contains_all($b, $a);
}

/**
 * Splits the array of rows into groups when the specified column value changes.
 * Note that the rows must be sorted by the column used to divide results.
 * @template T of array<string, mixed>
 * @param iterable<mixed, T> $rows
 * @return \Generator<int, list<T>>
 */
function group_rows(iterable $rows, string $groupColumn): \Generator
{
    $divideColVal = null;
    $itemSet = [];

    foreach ($rows as $row) {
        if ($divideColVal !== $row[$groupColumn]) {
            // new set of items

            if (!empty($itemSet)) {
                yield $itemSet; // yield previous set
            }

            $itemSet = [$row]; // start over
            /** @var mixed $divideColVal */
            $divideColVal = $row[$groupColumn];
        } else {
            // same set of items
            $itemSet[] = $row;
        }
    }

    if (!empty($itemSet)) {
        yield $itemSet;
    }
}

/**
 * Returns the specified array key value if it is a string.
 * @throws OutOfBoundsException If the array key does not exist.
 * @throws UnexpectedValueException If the array value is not a string.
 */
function require_str_key(array $data, string $key): string
{
    if (!isset($data[$key])) {
        throw new OutOfBoundsException("Missing required key: {$key}");
    }

    if (is_string($data[$key])) {
        return $data[$key];
    }

    $type = gettype($data[$key]);
    throw new UnexpectedValueException("{$key} value must be a string, {$type} given");
}

/**
 * Returns the specified array key value if it is a string, or null if the array key doesn't exist.
 * @throws UnexpectedValueException If the array value is not a string.
 */
function get_optional_str_key(array $data, string $key): ?string
{
    if (!isset($data[$key])) {
        return null;
    }

    return require_str_key($data, $key);
}

/**
 * Returns the specified array key value if it is an int or float.
 * @throws OutOfBoundsException If the array key does not exist.
 * @throws UnexpectedValueException If the array value is not an int or float.
 */
function require_numeric_key(array $data, string $key): float
{
    if (!isset($data[$key])) {
        throw new OutOfBoundsException("Missing required key: {$key}");
    }

    if (is_int($data[$key]) || is_float($data[$key])) {
        return $data[$key];
    }

    $type = gettype($data[$key]);
    throw new UnexpectedValueException("{$key} value must be a number, {$type} given");
}

/**
 * Returns the specified array key value if it is an int or float, or null if the array key doesn't exist.
 * @throws UnexpectedValueException If the array value is not an int or float.
 */
function get_optional_numeric_key(array $data, string $key): ?float
{
    if (!isset($data[$key])) {
        return null;
    }

    return require_numeric_key($data, $key);
}

/**
 * Returns the specified array key value if it is an integer.
 * @throws OutOfBoundsException If the array key does not exist.
 * @throws UnexpectedValueException If the array value is not an integer.
 */
function require_int_key(array $data, string $key): int
{
    if (!isset($data[$key])) {
        throw new OutOfBoundsException("Missing required key: {$key}");
    }

    if (is_int($data[$key])) {
        return $data[$key];
    }

    $type = gettype($data[$key]);
    throw new UnexpectedValueException("{$key} value must be an integer, {$type} given");
}

/**
 * Returns the specified array key value if it is an integer, or null if the array key doesn't exist.
 * @throws UnexpectedValueException If the array value is not an integer.
 */
function get_optional_int_key(array $data, string $key): ?int
{
    if (!isset($data[$key])) {
        return null;
    }

    return require_int_key($data, $key);
}

/**
 * Returns the specified array key value if it is a boolean.
 * @throws OutOfBoundsException If the array key does not exist.
 * @throws UnexpectedValueException If the array value is not a boolean.
 */
function require_bool_key(array $data, string $key): bool
{
    if (!isset($data[$key])) {
        throw new OutOfBoundsException("Missing required key: {$key}");
    }

    if (is_bool($data[$key])) {
        return $data[$key];
    }

    $type = gettype($data[$key]);
    throw new UnexpectedValueException("{$key} value must be a boolean, {$type} given");
}

/**
 * Returns the specified array key value if it is a boolean, or null if the array key doesn't exist.
 * @throws UnexpectedValueException If the array value is not a boolean.
 */
function get_optional_bool_key(array $data, string $key): ?bool
{
    if (!isset($data[$key])) {
        return null;
    }

    return require_bool_key($data, $key);
}
