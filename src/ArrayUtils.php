<?php

namespace theodorejb\ArrayUtils;

/**
 * Returns true if all the needles are in the haystack
 *
 * @param array $needles
 * @param array $haystack
 * @return bool
 */
function contains_all(array $needles, array $haystack)
{
    // return false if any of the needles aren't in the haystack
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
 *
 * @param array $a
 * @param array $b
 * @return bool
 */
function contains_same(array $a, array $b)
{
    return contains_all($a, $b) && contains_all($b, $a);
}

/**
 * Splits the array of rows into groups when the specified column value changes.
 * Note that the rows must be sorted by the column used to divide results.
 *
 * @param array $rows
 * @param string $groupColumn
 * @return \Iterator
 */
function group_rows(array $rows, $groupColumn)
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
