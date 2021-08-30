[![Maintainability](https://api.codeclimate.com/v1/badges/d521639c6e75a3eeebb7/maintainability)](https://codeclimate.com/github/ava239/php-oop-project-lvl1/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/d521639c6e75a3eeebb7/test_coverage)](https://codeclimate.com/github/ava239/php-oop-project-lvl1/test_coverage)

## Validator library inspired by yup
Can validate strings, numbers, arrays by shape.
Also supports custom validators

Usage example:
```php
<?php

use Ava239\Validator\Validator;

$v = new \Ava239\Validator\Validator();

// strings
$schema = $v->required()->string();

$schema->isValid('what does the fox say'); // true
$schema->isValid(''); // false

// numbers
$schema = $v->required()->number()->positive();

$schema->isValid(-10); // false
$schema->isValid(10); // true

// array with shape check
$schema = $v->array()->sizeof(2)->shape([
    'name' => $v->string()->required(),
    'age' => $v->number()->positive(),
]);

$schema->isValid(['name' => 'kolya', 'age' => 100]); // true
$schema->isValid(['name' => '', 'age' => null]); // false

// custom validator 
$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);

$schema = $v->string()->test('startWith', 'H');

$schema->isValid('exlet'); // false
$schema->isValid('Hexlet'); // true
```

More examples in tests