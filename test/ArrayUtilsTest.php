<?php

use theodorejb\ArrayUtils;

class ArrayUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testContainsAll()
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils\contains_all([1, 2], [3, 2, 1]));

        // types must match
        $this->assertFalse(ArrayUtils\contains_all([1, 2], ["1", "2"]));

        $this->assertFalse(ArrayUtils\contains_all([1, 2], [1]));
    }

    public function testContainsSame()
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils\contains_same([1, 2], [2, 1]));

        $this->assertFalse(ArrayUtils\contains_same([1, 2], [3, 2, 1]));
    }

    public function testGroupRows()
    {
        // an array retrieved by joining people and pets tables
        $peoplePets = [
            ['name' => 'Jack', 'petName' => 'Scruffy'],
            ['name' => 'Jack', 'petName' => 'Spot'],
            ['name' => 'Jack', 'petName' => 'Paws'],
            ['name' => 'Amy', 'petName' => 'Blackie'],
            ['name' => 'Amy', 'petName' => 'Whiskers']
        ];

        // the expected array grouped by name
        $expected = [
            [$peoplePets[0], $peoplePets[1], $peoplePets[2]],
            [$peoplePets[3], $peoplePets[4]],
        ];

        $actual = [];

        foreach (ArrayUtils\group_rows($peoplePets, 'name') as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
    }
}
