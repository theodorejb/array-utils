# ArrayUtils

[![Packagist Version](https://img.shields.io/packagist/v/theodorejb/array-utils.svg)](https://packagist.org/packages/theodorejb/array-utils)

ArrayUtils is a collection of useful PHP array functions.

## Install via Composer

`composer require theodorejb/array-utils`

## Methods

### containsAll

Returns true if all the needles are in the haystack.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$haystack = [1, 2, 3, 5, 8, 13];
$needles = [2, 13, 5];
ArrayUtils::containsAll($needles, $haystack); // true
ArrayUtils::containsAll($haystack, $needles); // false
```

### containsSame

Returns true if both arrays contain the same values (regardless of order).

```php
use theodorejb\ArrayUtils\ArrayUtils;

$set1 = [1, 3, 5, 7];
$set2 = [3, 7, 5, 1];

ArrayUtils::containsSame($set1, $set2); // true
```

### groupRows

Splits the iterable of associative arrays into groups when the specified key
value changes. The iterable must be sorted by the array key used to group results.

```php
use theodorejb\ArrayUtils\ArrayUtils;

// obtained by joining tables of people and their pets
$peoplePets = [
    ['name' => 'Jack', 'petName' => 'Scruffy'],
    ['name' => 'Jack', 'petName' => 'Spot'],
    ['name' => 'Jack', 'petName' => 'Paws'],
    ['name' => 'Amy', 'petName' => 'Blackie'],
    ['name' => 'Amy', 'petName' => 'Whiskers']
];

$grouped = [];

foreach (ArrayUtils::groupRows($peoplePets, 'name') as $group) {
    $grouped[] = $group;
}

$expected = [
    [
        $peoplePets[0],
        $peoplePets[1],
        $peoplePets[2],
    ],
    [
        $peoplePets[3],
        $peoplePets[4],
    ]
];

var_dump($grouped === $expected); // bool(true)
```

### requireStrKey

Returns the specified array key value if it is a string. Throws an exception otherwise.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'i' => 1];
ArrayUtils::requireStrKey($data, 'k'); // val
ArrayUtils::requireStrKey($data, 'x'); // throws OutOfBoundsException
ArrayUtils::requireStrKey($data, 'i'); // throws UnexpectedValueException
```

### getOptionalStrKey

Returns the specified array key value if it is a string, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not a string.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'i' => 1];
ArrayUtils::getOptionalStrKey($data, 'k'); // val
ArrayUtils::getOptionalStrKey($data, 'x'); // null
ArrayUtils::getOptionalStrKey($data, 'i'); // throws UnexpectedValueException
```

### requireNumericKey

Returns the specified array key value as a float if it is an integer or float. Throws an exception otherwise.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['i' => 1, 'f' => 0.5, 'k' => 'val'];
ArrayUtils::requireNumericKey($data, 'i'); // 1.0
ArrayUtils::requireNumericKey($data, 'f'); // 0.5
ArrayUtils::requireNumericKey($data, 'x'); // throws OutOfBoundsException
ArrayUtils::requireNumericKey($data, 'k'); // throws UnexpectedValueException
```

### getOptionalNumericKey

Returns the specified array key value as a float if it is an integer or float, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not an integer or float.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['i' => 2, 'f' => 0.5, 'k' => 'val'];
ArrayUtils::getOptionalNumericKey($data, 'i'); // 2.0
ArrayUtils::getOptionalNumericKey($data, 'f'); // 0.5
ArrayUtils::getOptionalNumericKey($data, 'x'); // null
ArrayUtils::getOptionalNumericKey($data, 'k'); // throws UnexpectedValueException
```

### requireIntKey

Returns the specified array key value if it is an integer. Throws an exception otherwise.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'i' => 1];
ArrayUtils::requireIntKey($data, 'i'); // 1
ArrayUtils::requireIntKey($data, 'x'); // throws OutOfBoundsException
ArrayUtils::requireIntKey($data, 'k'); // throws UnexpectedValueException
```

### getOptionalIntKey

Returns the specified array key value if it is an integer, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not an integer.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'i' => 2];
ArrayUtils::getOptionalIntKey($data, 'i'); // 2
ArrayUtils::getOptionalIntKey($data, 'x'); // null
ArrayUtils::getOptionalIntKey($data, 'k'); // throws UnexpectedValueException
```

### requireBoolKey

Returns the specified array key value if it is a boolean. Throws an exception otherwise.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'b' => true];
ArrayUtils::requireBoolKey($data, 'b'); // true
ArrayUtils::requireBoolKey($data, 'x'); // throws OutOfBoundsException
ArrayUtils::requireBoolKey($data, 'k'); // throws UnexpectedValueException
```

### getOptionalBoolKey

Returns the specified array key value if it is a boolean, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not a boolean.

```php
use theodorejb\ArrayUtils\ArrayUtils;

$data = ['k' => 'val', 'b' => false];
ArrayUtils::getOptionalBoolKey($data, 'b'); // false
ArrayUtils::getOptionalBoolKey($data, 'x'); // null
ArrayUtils::getOptionalBoolKey($data, 'k'); // throws UnexpectedValueException
```

## Author

Theodore Brown  
<https://theodorejb.me>

## License

MIT
