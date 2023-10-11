[![Maintainability](https://api.codeclimate.com/v1/badges/9a08cc43b10ca962f95e/maintainability)](https://codeclimate.com/github/ava239/validator/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9a08cc43b10ca962f95e/test_coverage)](https://codeclimate.com/github/ava239/validator/test_coverage)

# Validator library
Inspired by [yup](https://github.com/jquense/yup) (JavaScript library)  
Can validate strings, numbers, arrays by shape.  
Also supports custom validators.

Basic usage example:
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
$schema->isValid('Hack'); // true
```

More examples in tests

## Factory object
```php
$v = new \Ava239\Validator\Validator();
```
This will return factory object. It`s basic for your later usage and only one instance is really needed (but you can create as many as you wish).  
It can produce concrete validator instances which implement ```ValidatorBase``` interface and supposed to validate single variable of defined type.  
Types implemented at this moment:
- **string** (created by ```string()``` method)
- **number** (created by ```number()``` method)
- **array** (created by ```array()``` method)

This object also responsible for adding custom validators to concrete validation objects created by it.   
Use ```addValidator($type, $name, $validatorFunction)``` to add it, where:
- ```$type``` is one of ```string```,```number```,```array```. defines data type where validator will be available
- ```$name``` is validator name. you will call it by that name
- ```$validatorFunction``` is closure with at least one parameter which returns boolean. First parameter passed to function will be thing we are validating

## Running custom validations
Custom validations called after adding custom validator to factory object.  
Full example:
```php
$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);

$schema = $v->string()->test('startWith', 'H');
```
You call ```test()``` function on concrete validator with such params:
- validator name
- any number of additional params *(optional)*

## List of included validators by data type
### String
- ```contains($substring)``` - tests if string contains **$substring**
- ```minLength($length)``` - tests if string length is at least **$length**
- ```maxLength($length)``` - tests if string length does not exceed **$length**
- ```required()``` - tests if string is not empty
### Number
- ```positive()``` - tests if number is positive (> 0)
- ```range($min, $max)``` - tests if number is between **$min** and **$max**
- ```required()``` - tests if value is a number (without this null is also valid value)
### Array
- ```sizeof($length)``` - tests if array contains at least **$length** elements
- ```required()``` - tests if value is an array (without this null is also valid value)
- ```shape($shape)``` - tests if array is described by a **$shape**, where **$shape** is associative array where values are validator objects created by a ```factory object```.  
This method returns true only if every sub-validator is valid.  
Allows any reasonable level of nesting (you can put array with shape validation as a key for shape validation).

## Error messages
As of version *1.1.0* you can add error messages for each validation.  
To get error list there was ```validate($data)``` method added which will act as ```isValid($data)``` with a difference it will return validator object, so you can chain ```getErrors()``` method.  
Let's get to example:
```php
$schema->shape(
    [
        'name' => $v->string()->required("error name"),
        'age' => $v->number()->positive("error age"),
        'surname' => $v->string()->required("error surname"),
        'passport' => $v->array()->required()->shape(
            [
                'number' => $v->number()->required("error number"),
                'series' => $v->number()->required("error series"),
                'sub' => $v->array()->required()->shape(
                    [
                        'num' => $v->number()->required("error subnum"),
                    ],
                    'error sub'
                ),
            ],
            'error passport'
        ),
    ],
    'error array'
); 
```
Here we define shape with error messages for most of the validations.  
You can define error message as last parameter of any validation function.  
So if it is validation with params you call it like that: ```contains('substring', 'error message')```

You also can define parameter name to use it in error messages. To do so just add as parameter on validator creation call (```string()```, ```number()``` or ```array()```).  
It can be accessed as ```{{name}}``` in any validation message:
```php
$fn = fn ($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn, 'test msg {{name}}');
$v->string('test name')->test('startWith', 'H'); 
```
If error occurs message will be parsed to **"test msg test name"**

### Error array structure
It is flat associative array (no matter how much nesting you used) ```key => message```.  
Keys have such structure:
- validation name for any basic validation
- ```_``` for shape validation
- nested keys represented by dot notation.  
Keys for example above:
```php
'_' // shape error
'age.positive' // age field, positive validation error
'name.required' // name field, required validation error
'passport._' // passport field, shape error
'passport.series.required' // passport field, series sub-field, required validation error
'passport.number.valid' // passport field, number sub-field, number validation error
// if value doesn't match expected data type it generates error with key "valid"
// it's applied for array, number, string validations
// if this error occurs no further validations will be performed for this field
'passport.sub._' // passport field, "sub" sub-field, shape error
'passport.sub.num.required' // passport field, "sub" sub-field, "num" sub-sub-field required validation error
```
Notice above how data type errors handled.

**It is expected what you convert error messages structure as you wish after you get them.**