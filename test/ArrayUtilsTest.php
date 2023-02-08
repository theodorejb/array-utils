<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use theodorejb\ArrayUtils;

class ArrayUtilsTest extends TestCase
{
    public function testContainsAll(): void
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils\contains_all([1, 2], [3, 2, 1]));

        // types must match
        $this->assertFalse(ArrayUtils\contains_all([1, 2], ["1", "2"]));

        $this->assertFalse(ArrayUtils\contains_all([1, 2], [1]));
    }

    public function testContainsSame(): void
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils\contains_same([1, 2], [2, 1]));

        $this->assertFalse(ArrayUtils\contains_same([1, 2], [3, 2, 1]));
    }

    public function testGroupRows(): void
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
        $names = [];

        foreach (ArrayUtils\group_rows($peoplePets, 'petName') as $group) {
            $names[] = $group[0]['name'];
        }

        $expected = ['Jack', 'Jack', 'Jack', 'Amy', 'Amy'];
        $this->assertSame($expected, $names);
    }

    public function testGroupRowsFalsyGroupValues(): void
    {
        $rows = [
            ['name' => null, 'petName' => 'Blackie'],
            ['name' => null, 'petName' => 'Whiskers'],
            ['name' => false, 'petName' => 'Scruffy'],
            ['name' => false, 'petName' => 'Spot'],
            ['name' => 0, 'petName' => 'Paws'],
            ['name' => 0, 'petName' => 'Claws'],
            ['name' => '', 'petName' => 'Puffball'],
            ['name' => '', 'petName' => 'Tinker'],
        ];

        $expected = [
            [$rows[0], $rows[1]],
            [$rows[2], $rows[3]],
            [$rows[4], $rows[5]],
            [$rows[6], $rows[7]],
        ];

        $actual = [];

        foreach (ArrayUtils\group_rows($rows, 'name') as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
    }

    public function testGroupRowsEmptyArray(): void
    {
        $this->expectNotToPerformAssertions();

        foreach (ArrayUtils\group_rows([], 'test') as $_group) {
            $this->fail('Empty array incorrectly resulted in yield');
        }
    }

    public function testGroupRowsTraversable(): void
    {
        $generate = function (): Generator {
            for ($i = 0; $i < 4; $i++) {
                $set = $i < 2 ? 1 : 2;
                yield ['set' => $set, 'i' => $i];
            }
        };

        $groups = [];

        foreach (ArrayUtils\group_rows($generate(), 'set') as $group) {
            $groups[] = $group;
        }

        $expected = [
            [
                ['set' => 1, 'i' => 0],
                ['set' => 1, 'i' => 1],
            ],
            [
                ['set' => 2, 'i' => 2],
                ['set' => 2, 'i' => 3],
            ],
        ];

        $this->assertSame($expected, $groups);
    }

    public function testRequireStrKey(): void
    {
        $this->assertSame('v', ArrayUtils\require_str_key(['k' => 'v'], 'k'));

        try {
            ArrayUtils\require_str_key([], 'foo');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: foo', $e->getMessage());
        }

        try {
            ArrayUtils\require_str_key(['k' => 1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a string, integer given', $e->getMessage());
        }
    }

    public function testGetOptionalStrKey(): void
    {
        $this->assertSame('v', ArrayUtils\get_optional_str_key(['k' => 'v'], 'k'));
        $this->assertNull(ArrayUtils\get_optional_str_key([], 'missing'));

        try {
            ArrayUtils\get_optional_str_key(['k' => 1.1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a string, double given', $e->getMessage());
        }
    }

    public function testRequireNumericKey(): void
    {
        $data = ['quantity' => 2, 'amount' => 5.95];
        $this->assertSame(2.0, ArrayUtils\require_numeric_key($data, 'quantity'));
        $this->assertSame(5.95, ArrayUtils\require_numeric_key($data, 'amount'));

        try {
            ArrayUtils\require_numeric_key($data, 'bar');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: bar', $e->getMessage());
        }

        try {
            ArrayUtils\require_numeric_key(['k' => '1'], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a number, string given', $e->getMessage());
        }
    }

    public function testGetOptionalNumericKey(): void
    {
        $this->assertSame(1.2, ArrayUtils\get_optional_numeric_key(['k' => 1.2], 'k'));
        $this->assertNull(ArrayUtils\get_optional_numeric_key([], 'missing'));

        try {
            ArrayUtils\get_optional_numeric_key(['foo' => true], 'foo');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('foo value must be a number, boolean given', $e->getMessage());
        }
    }

    public function testRequireIntKey(): void
    {
        $this->assertSame(1, ArrayUtils\require_int_key(['k' => 1], 'k'));

        try {
            ArrayUtils\require_int_key([], 'baz');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: baz', $e->getMessage());
        }

        try {
            ArrayUtils\require_int_key(['k' => '1'], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be an integer, string given', $e->getMessage());
        }
    }

    public function testGetOptionalIntKey(): void
    {
        $this->assertSame(2, ArrayUtils\get_optional_int_key(['k' => 2], 'k'));
        $this->assertNull(ArrayUtils\get_optional_int_key([], 'missing'));

        try {
            ArrayUtils\get_optional_int_key(['k' => 1.1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be an integer, double given', $e->getMessage());
        }
    }

    public function testRequireBoolKey(): void
    {
        $this->assertTrue(ArrayUtils\require_bool_key(['k' => true], 'k'));
        $this->assertFalse(ArrayUtils\require_bool_key(['k' => false], 'k'));

        try {
            ArrayUtils\require_bool_key([], 'fizz');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: fizz', $e->getMessage());
        }

        try {
            ArrayUtils\require_bool_key(['k' => 1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a boolean, integer given', $e->getMessage());
        }
    }

    public function testGetOptionalBoolKey(): void
    {
        $this->assertSame(true, ArrayUtils\get_optional_bool_key(['k' => true], 'k'));
        $this->assertNull(ArrayUtils\get_optional_bool_key([], 'missing'));

        try {
            ArrayUtils\get_optional_bool_key(['k' => []], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a boolean, array given', $e->getMessage());
        }
    }
}
