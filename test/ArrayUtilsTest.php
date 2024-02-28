<?php

declare(strict_types=1);

namespace theodorejb\ArrayUtils\Test;

use Generator;
use OutOfBoundsException;
use UnexpectedValueException;
use PHPUnit\Framework\TestCase;
use theodorejb\ArrayUtils\ArrayUtils;

class ArrayUtilsTest extends TestCase
{
    /**
     * An array retrieved by joining people and pets tables
     * @var list<array{lName: string, fName: string, pet: string}>
     */
    private array $peoplePets = [
        ['lName' => 'Jordan', 'fName' => 'Jack', 'pet' => 'Scruffy'],
        ['lName' => 'Jordan', 'fName' => 'Jack', 'pet' => 'Spot'],
        ['lName' => 'Jordan', 'fName' => 'Jill', 'pet' => 'Paws'],
        ['lName' => 'Greene', 'fName' => 'Amy',  'pet' => 'Blackie'],
        ['lName' => 'Greene', 'fName' => 'Amy',  'pet' => 'Whiskers'],
        ['lName' => 'Greene', 'fName' => 'Amy',  'pet' => 'Paws'],
        ['lName' => 'Smith',  'fName' => 'Amy',  'pet' => 'Tiger'],
    ];

    public function testContainsAll(): void
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils::containsAll([1, 2], [3, 2, 1]));

        // types must match
        $this->assertFalse(ArrayUtils::containsAll([1, 2], ["1", "2"]));

        $this->assertFalse(ArrayUtils::containsAll([1, 2], [1]));
    }

    public function testContainsSame(): void
    {
        // order shouldn't matter
        $this->assertTrue(ArrayUtils::containsSame([1, 2], [2, 1]));

        $this->assertFalse(ArrayUtils::containsSame([1, 2], [3, 2, 1]));
    }

    public function testGroupRows(): void
    {
        $peoplePets = $this->peoplePets;

        // the expected array grouped by first name
        $expected = [
            [$peoplePets[0], $peoplePets[1]],
            [$peoplePets[2]],
            [$peoplePets[3], $peoplePets[4], $peoplePets[5], $peoplePets[6]],
        ];

        $actual = [];

        foreach (ArrayUtils::groupRows($peoplePets, 'fName') as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
        $names = [];

        foreach (ArrayUtils::groupRows($peoplePets, 'pet') as $group) {
            $names[] = $group[0]['fName'];
        }

        $expected = ['Jack', 'Jack', 'Jill', 'Amy', 'Amy', 'Amy', 'Amy'];
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

        foreach (ArrayUtils::groupRows($rows, 'name') as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
    }

    public function testGroupRowsEmptyArray(): void
    {
        $this->expectNotToPerformAssertions();

        foreach (ArrayUtils::groupRows([], 'test') as $_group) {
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

        foreach (ArrayUtils::groupRows($generate(), 'set') as $group) {
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

    public function testGroupRowsIntKey(): void
    {
        // an array retrieved by joining people and pets tables
        $peoplePets = [
            ['Jack', 'Scruffy'],
            ['Jack', 'Spot'],
            ['Jack', 'Paws'],
            ['Amy', 'Blackie'],
            ['Amy', 'Whiskers']
        ];

        // the expected array grouped by name
        $expected = [
            [$peoplePets[0], $peoplePets[1], $peoplePets[2]],
            [$peoplePets[3], $peoplePets[4]],
        ];

        $actual = [];

        foreach (ArrayUtils::groupRows($peoplePets, 0) as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
        $names = [];

        foreach (ArrayUtils::groupRows($peoplePets, 1) as $group) {
            $names[] = $group[0][0];
        }

        $expected = ['Jack', 'Jack', 'Jack', 'Amy', 'Amy'];
        $this->assertSame($expected, $names);
    }

    public function testGroupRowsTwoColumns(): void
    {
        $peoplePets = $this->peoplePets;

        // the expected array grouped by last and first name
        $expected = [
            [$peoplePets[0], $peoplePets[1]],
            [$peoplePets[2]],
            [$peoplePets[3], $peoplePets[4], $peoplePets[5]],
            [$peoplePets[6]],
        ];

        $actual = [];

        foreach (ArrayUtils::groupRows($peoplePets, 'lName', 'fName') as $group) {
            $actual[] = $group;
        }

        $this->assertSame($expected, $actual);
    }

    public function testGetSafeInteger(): void
    {
        $array = [
            'actualInt' => 1,
            'strInt' => '1',
            'unsafeInt' => '01234',
            'badStr' => 'foo',
            'myFloat' => 1.0,
        ];

        $this->assertSame(1, ArrayUtils::getSafeInteger($array, 'actualInt'));
        $this->assertSame(1, ArrayUtils::getSafeInteger($array, 'strInt'));
        $this->assertNull(ArrayUtils::getSafeInteger($array, 'nonexistent'));

        try {
            ArrayUtils::getSafeInteger($array, 'unsafeInt');
            $this->fail('Failed to throw exception for unsafe int string');
        } catch (\Exception $e) {
            $this->assertSame('unsafeInt value must be an integer, string given', $e->getMessage());
        }

        try {
            ArrayUtils::getSafeInteger($array, 'badStr');
            $this->fail('Failed to throw exception for invalid key type');
        } catch (\Exception $e) {
            $this->assertSame('badStr value must be an integer, string given', $e->getMessage());
        }

        try {
            ArrayUtils::getSafeInteger($array, 'myFloat');
            $this->fail('Failed to throw exception for invalid key type');
        } catch (\Exception $e) {
            $this->assertSame('myFloat value must be an integer, float given', $e->getMessage());
        }
    }

    public function testRequireStrKey(): void
    {
        $this->assertSame('v', ArrayUtils::requireStrKey(['k' => 'v'], 'k'));

        try {
            ArrayUtils::requireStrKey([], 'foo');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: foo', $e->getMessage());
        }

        try {
            ArrayUtils::requireStrKey(['k' => 1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a string, integer given', $e->getMessage());
        }
    }

    public function testGetOptionalStrKey(): void
    {
        $this->assertSame('v', ArrayUtils::getOptionalStrKey(['k' => 'v'], 'k'));
        $this->assertNull(ArrayUtils::getOptionalStrKey([], 'missing'));

        try {
            ArrayUtils::getOptionalStrKey(['k' => 1.1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a string, double given', $e->getMessage());
        }
    }

    public function testRequireNumericKey(): void
    {
        $data = ['quantity' => 2, 'amount' => 5.95];
        $this->assertSame(2.0, ArrayUtils::requireNumericKey($data, 'quantity'));
        $this->assertSame(5.95, ArrayUtils::requireNumericKey($data, 'amount'));

        try {
            ArrayUtils::requireNumericKey($data, 'bar');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: bar', $e->getMessage());
        }

        try {
            ArrayUtils::requireNumericKey(['k' => '1'], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a number, string given', $e->getMessage());
        }
    }

    public function testGetOptionalNumericKey(): void
    {
        $this->assertSame(1.2, ArrayUtils::getOptionalNumericKey(['k' => 1.2], 'k'));
        $this->assertNull(ArrayUtils::getOptionalNumericKey([], 'missing'));

        try {
            ArrayUtils::getOptionalNumericKey(['foo' => true], 'foo');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('foo value must be a number, boolean given', $e->getMessage());
        }
    }

    public function testRequireIntKey(): void
    {
        $this->assertSame(1, ArrayUtils::requireIntKey(['k' => 1], 'k'));

        try {
            ArrayUtils::requireIntKey([], 'baz');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: baz', $e->getMessage());
        }

        try {
            ArrayUtils::requireIntKey(['k' => '1'], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be an integer, string given', $e->getMessage());
        }
    }

    public function testGetOptionalIntKey(): void
    {
        $this->assertSame(2, ArrayUtils::getOptionalIntKey(['k' => 2], 'k'));
        $this->assertNull(ArrayUtils::getOptionalIntKey([], 'missing'));

        try {
            ArrayUtils::getOptionalIntKey(['k' => 1.1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be an integer, double given', $e->getMessage());
        }
    }

    public function testRequireBoolKey(): void
    {
        $this->assertTrue(ArrayUtils::requireBoolKey(['k' => true], 'k'));
        $this->assertFalse(ArrayUtils::requireBoolKey(['k' => false], 'k'));

        try {
            ArrayUtils::requireBoolKey([], 'fizz');
            $this->fail('Failed to throw exception for missing key');
        } catch (OutOfBoundsException $e) {
            $this->assertSame('Missing required key: fizz', $e->getMessage());
        }

        try {
            ArrayUtils::requireBoolKey(['k' => 1], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a boolean, integer given', $e->getMessage());
        }
    }

    public function testGetOptionalBoolKey(): void
    {
        $this->assertSame(true, ArrayUtils::getOptionalBoolKey(['k' => true], 'k'));
        $this->assertNull(ArrayUtils::getOptionalBoolKey([], 'missing'));

        try {
            ArrayUtils::getOptionalBoolKey(['k' => []], 'k');
            $this->fail('Failed to throw exception for incorrect type');
        } catch (UnexpectedValueException $e) {
            $this->assertSame('k value must be a boolean, array given', $e->getMessage());
        }
    }
}
