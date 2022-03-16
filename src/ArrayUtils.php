<?php

declare(strict_types=1);

namespace theodorejb\ArrayUtils;

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
