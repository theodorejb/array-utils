<?php

declare(strict_types=1);

namespace theodorejb\ArrayUtils;

use Generator;
use OutOfBoundsException;
use UnexpectedValueException;

class ArrayUtils
{
    /**
     * Returns true if all the needles are in the haystack
     */
    public static function containsAll(array $needles, array $haystack): bool
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
    public static function containsSame(array $a, array $b): bool
    {
        return self::containsAll($a, $b) && self::containsAll($b, $a);
    }

    /**
     * Splits the rows into groups when the specified column values change.
     * Note that the rows must be sorted by the columns used to group results.
     * @template T of array
     * @param iterable<T> $rows
     * @return Generator<int, list<T>>
     */
    public static function groupRows(iterable $rows, string|int ...$groupBy): Generator
    {
        $groupColVals = array_fill_keys($groupBy, null);
        $itemSet = [];

        foreach ($rows as $row) {
            $newSet = false;

            /** @var mixed $val */
            foreach ($groupColVals as $col => $val) {
                /** @var mixed $rowVal */
                $rowVal = $row[$col];

                if ($val !== $rowVal) {
                    $newSet = true;
                    /** @psalm-suppress MixedAssignment */
                    $groupColVals[$col] = $rowVal;
                }
            }

            if ($newSet) {
                if (!empty($itemSet)) {
                    yield $itemSet; // yield previous set
                }

                $itemSet = [$row]; // start over
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
     * If the specified array key value is an integer or a string which can be cast to an integer
     * without data loss, returns the value as an int. Returns null if the array key doesn't exist.
     * @throws UnexpectedValueException If the array value cannot be safely cast to an integer.
     */
    public static function getSafeInteger(array $data, string $key): ?int
    {
        if (!isset($data[$key])) {
            return null;
        }

        /** @psalm-suppress MixedAssignment */
        $value = $data[$key];

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            if ((string) (int) $value === $value) {
                return (int) $value;
            }
        }

        $type = get_debug_type($data[$key]);
        throw new UnexpectedValueException("$key value must be an integer, $type given");
    }

    /**
     * Returns the specified array key value if it is a string.
     * @throws OutOfBoundsException If the array key does not exist.
     * @throws UnexpectedValueException If the array value is not a string.
     */
    public static function requireStrKey(array $data, string $key): string
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
    public static function getOptionalStrKey(array $data, string $key): ?string
    {
        if (!isset($data[$key])) {
            return null;
        }

        return self::requireStrKey($data, $key);
    }

    /**
     * Returns the specified array key value if it is an int or float.
     * @throws OutOfBoundsException If the array key does not exist.
     * @throws UnexpectedValueException If the array value is not an int or float.
     */
    public static function requireNumericKey(array $data, string $key): float
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
    public static function getOptionalNumericKey(array $data, string $key): ?float
    {
        if (!isset($data[$key])) {
            return null;
        }

        return self::requireNumericKey($data, $key);
    }

    /**
     * Returns the specified array key value if it is an integer.
     * @throws OutOfBoundsException If the array key does not exist.
     * @throws UnexpectedValueException If the array value is not an integer.
     */
    public static function requireIntKey(array $data, string $key): int
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
    public static function getOptionalIntKey(array $data, string $key): ?int
    {
        if (!isset($data[$key])) {
            return null;
        }

        return self::requireIntKey($data, $key);
    }

    /**
     * Returns the specified array key value if it is a boolean.
     * @throws OutOfBoundsException If the array key does not exist.
     * @throws UnexpectedValueException If the array value is not a boolean.
     */
    public static function requireBoolKey(array $data, string $key): bool
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
    public static function getOptionalBoolKey(array $data, string $key): ?bool
    {
        if (!isset($data[$key])) {
            return null;
        }

        return self::requireBoolKey($data, $key);
    }
}
