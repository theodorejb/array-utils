# ArrayUtils

[![Packagist Version](https://img.shields.io/packagist/v/theodorejb/array-utils.svg)](https://packagist.org/packages/theodorejb/array-utils)

ArrayUtils is a collection of useful PHP array functions.

## Install via Composer

`composer require theodorejb/array-utils`

## Functions

### contains_all

Returns true if all the needles are in the haystack.

```php
use function theodorejb\ArrayUtils\contains_all;

$haystack = [1, 2, 3, 5, 8, 13];
$needles = [2, 13, 5];
contains_all($needles, $haystack); // true
contains_all($haystack, $needles); // false
```

### contains_same

Returns true if both arrays contain the same values (regardless of order).

```php
use function theodorejb\ArrayUtils\contains_same;

$set1 = [1, 3, 5, 7];
$set2 = [3, 7, 5, 1];

contains_same($set1, $set2); // true
```

### group_rows

Splits the iterable of associative arrays into groups when the specified key
value changes. The iterable must be sorted by the array key used to group results.

```php
use function theodorejb\ArrayUtils\group_rows;

// obtained by joining tables of people and their pets
$peoplePets = [
    ['name' => 'Jack', 'petName' => 'Scruffy'],
    ['name' => 'Jack', 'petName' => 'Spot'],
    ['name' => 'Jack', 'petName' => 'Paws'],
    ['name' => 'Amy', 'petName' => 'Blackie'],
    ['name' => 'Amy', 'petName' => 'Whiskers']
];

$grouped = [];

foreach (group_rows($peoplePets, 'name') as $group) {
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

### require_str_key

Returns the specified array key value if it is a string. Throws an exception otherwise.

```php
use function theodorejb\ArrayUtils\require_str_key;

$data = ['k' => 'val', 'i' => 1];
require_str_key($data, 'k'); // val
require_str_key($data, 'x'); // throws OutOfBoundsException
require_str_key($data, 'i'); // throws UnexpectedValueException
```

### get_optional_str_key

Returns the specified array key value if it is a string, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not a string.

```php
use function theodorejb\ArrayUtils\get_optional_str_key;

$data = ['k' => 'val', 'i' => 1];
get_optional_str_key($data, 'k'); // val
get_optional_str_key($data, 'x'); // null
get_optional_str_key($data, 'i'); // throws UnexpectedValueException
```

### require_numeric_key

Returns the specified array key value as a float if it is an integer or float. Throws an exception otherwise.

```php
use function theodorejb\ArrayUtils\require_numeric_key;

$data = ['i' => 1, 'f' => 0.5, 'k' => 'val'];
require_numeric_key($data, 'i'); // 1.0
require_numeric_key($data, 'f'); // 0.5
require_numeric_key($data, 'x'); // throws OutOfBoundsException
require_numeric_key($data, 'k'); // throws UnexpectedValueException
```

### get_optional_numeric_key

Returns the specified array key value as a float if it is an integer or float, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not an integer or float.

```php
use function theodorejb\ArrayUtils\get_optional_numeric_key;

$data = ['i' => 2, 'f' => 0.5, 'k' => 'val'];
get_optional_numeric_key($data, 'i'); // 2.0
get_optional_numeric_key($data, 'f'); // 0.5
get_optional_numeric_key($data, 'x'); // null
get_optional_numeric_key($data, 'k'); // throws UnexpectedValueException
```

### require_int_key

Returns the specified array key value if it is an integer. Throws an exception otherwise.

```php
use function theodorejb\ArrayUtils\require_int_key;

$data = ['k' => 'val', 'i' => 1];
require_int_key($data, 'i'); // 1
require_int_key($data, 'x'); // throws OutOfBoundsException
require_int_key($data, 'k'); // throws UnexpectedValueException
```

### get_optional_int_key

Returns the specified array key value if it is an integer, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not an integer.

```php
use function theodorejb\ArrayUtils\get_optional_int_key;

$data = ['k' => 'val', 'i' => 2];
get_optional_int_key($data, 'i'); // 2
get_optional_int_key($data, 'x'); // null
get_optional_int_key($data, 'k'); // throws UnexpectedValueException
```

### require_bool_key

Returns the specified array key value if it is a boolean. Throws an exception otherwise.

```php
use function theodorejb\ArrayUtils\require_bool_key;

$data = ['k' => 'val', 'b' => true];
require_bool_key($data, 'b'); // true
require_bool_key($data, 'x'); // throws OutOfBoundsException
require_bool_key($data, 'k'); // throws UnexpectedValueException
```

### get_optional_bool_key

Returns the specified array key value if it is a boolean, or `null` if the array key doesn't exist.
Throws an exception if the key exists but the value is not a boolean.

```php
use function theodorejb\ArrayUtils\get_optional_bool_key;

$data = ['k' => 'val', 'b' => false];
get_optional_bool_key($data, 'b'); // false
get_optional_bool_key($data, 'x'); // null
get_optional_bool_key($data, 'k'); // throws UnexpectedValueException
```

## Author

Theodore Brown  
<https://theodorejb.me>

## License

MIT
