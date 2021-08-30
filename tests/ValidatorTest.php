<?php

namespace Ava239\Validator\Tests;

use Ava239\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testCreate(): void
    {
        $validator = new Validator();

        $this->assertEquals(Validator::class, get_class($validator));
    }

    public function testString(): void
    {
        $v = new Validator();

        $schema = $v->string();

        $this->assertTrue($schema->isValid(''));
        $this->assertTrue($schema->isValid(null));

        $schema->required();

        $this->assertTrue($schema->isValid('what does the fox say'));
        $this->assertTrue($schema->isValid('hexlet'));
        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(''));

        $this->assertFalse($schema->minLength(5)->isValid('what'));
        $this->assertTrue($schema->isValid('what does'));

        $this->assertTrue($schema->contains('what')->isValid('what does the fox say'));
        $this->assertFalse($schema->contains('whatthe')->isValid('what does the fox say'));
    }

    public function testNumber(): void
    {
        $v = new Validator();

        $schema = $v->number();

        $this->assertTrue($schema->isValid(null));
        $this->assertFalse($schema->isValid(false));

        $this->assertTrue($schema->positive()->isValid(null));

        $schema->required();

        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(7));

        $this->assertTrue($schema->positive()->isValid(10));

        $schema->positive()->range(-5, 5);

        $this->assertFalse($schema->isValid(-3));
        $this->assertTrue($schema->isValid(5));
    }

    public function testArray(): void
    {
        $v = new Validator();

        $schema = $v->array();

        $this->assertTrue($schema->isValid([]));
        $this->assertTrue($schema->isValid(null));

        $schema->required();

        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid([]));
        $this->assertTrue($schema->isValid(['hexlet']));

        $schema->sizeof(2);

        $this->assertFalse($schema->isValid(['hexlet']));
        $this->assertTrue($schema->isValid(['hexlet', 'code-basics']));
    }

    public function testShape(): void
    {
        $v = new Validator();

        $schema = $v->array();

        $schema->shape([
            'name' => $v->string()->required(),
            'age' => $v->number()->positive(),
        ]);

        $this->assertTrue($schema->isValid(['name' => 'kolya', 'age' => 100]));
        $this->assertTrue($schema->isValid(['name' => 'maya', 'age' => null]));
        $this->assertFalse($schema->isValid(['name' => '', 'age' => null]));
        $this->assertFalse($schema->isValid(['name' => 'ada', 'age' => -5]));
    }

    public function testCustom(): void
    {
        $v = new Validator();

        $fn = function ($value, $start) {
            return str_starts_with($value, $start);
        };
        $v->addValidator('string', 'startWith', $fn);

        $schema = $v->string()->test('startWith', 'H');
        $this->assertFalse($schema->isValid('exlet'));
        $this->assertTrue($schema->isValid('Hexlet'));

        $fn = function ($value, $min) {
            return $value >= $min;
        };
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(6));
    }

    public function testStringErrors(): void
    {
        $v = new Validator();
        $schema = $v->string();
        $this->assertEquals([], $schema->getErrors());

        $this->assertEquals(['valid' => 'is_string'], $schema->validate(1)->getErrors());

        $message = 'test message';

        $this->assertEquals(['required' => $message], $schema->required($message)->validate(null)->getErrors());

        $errors = $schema->required()->minLength(5)->contains('what', $message)->validate(1)->getErrors();
        $this->assertEquals(['valid' => 'is_string', 'contains' => $message], $errors);
    }

    public function testNumberErrors(): void
    {
        $v = new Validator();
        $schema = $v->number();

        $this->assertEquals(['valid' => 'is_number'], $schema->validate('x')->getErrors());

        $messages = ['test message', 'test message 2'];

        $this->assertEquals(['required' => $messages[0]], $schema->required($messages[0])->validate(null)->getErrors());

        $errors = $schema->required()->positive($messages[0])->range(-1, 0, $messages[1])->validate(-11)->getErrors();
        $this->assertEquals(['in_range' => $messages[1], 'is_positive' => $messages[0]], $errors);
    }

    public function testArrayErrors(): void
    {
        $v = new Validator();
        $schema = $v->array();

        $this->assertEquals(['valid' => 'is_array'], $schema->validate('x')->getErrors());

        $message = 'test message';
        $expected = ['sizeof' => $message];

        $this->assertEquals($expected, $schema->required()->sizeof(3, $message)->validate([])->getErrors());
    }

    public function testShapeErrors(): void
    {
        $v = new Validator();
        $schema = $v->array();

        $message = 'test';

        $schema->shape([
            'name' => $v->string()->required("name {$message}"),
            'age' => $v->number()->positive("age {$message}"),
            'surname' => $v->string()->required("surname {$message}"),
        ], $message);
        $expected = [
            'shape' => $message,
            'shape.age' => [
                'is_positive' => "age {$message}",
            ],
        ];

        $this->assertEquals($expected, $schema->validate(['age' => -1])->getErrors());
    }
}
