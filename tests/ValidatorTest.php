<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use Hexlet\Validator\Validators\ArrayValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testCreate(): void
    {
        $validator = new Validator();

        $this->assertEquals(get_class($validator), Validator::class);
    }

    public function testString(): void
    {
        $v = new Validator();

        $schema = $v->string();

        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(''));

        $schema = $schema->required();

        $this->assertTrue($schema->isValid('what does the fox say'));
        $this->assertTrue($schema->isValid('hexlet'));
        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(''));

        $this->assertFalse($schema->minLength(5)->isValid('what'));
        $this->assertTrue($schema->minLength(5)->isValid('what does'));

        $this->assertTrue($schema->contains('what')->isValid('what does the fox say'));
        $this->assertFalse($schema->contains('whatthe')->isValid('what does the fox say'));
    }

    public function testNumber(): void
    {
        $v = new Validator();

        $schema = $v->number();

        $this->assertTrue($schema->isValid(null));

        $schema = $schema->required();

        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(7));

        $this->assertTrue($schema->positive()->isValid(10));

        $schema = $schema->positive()->range(-5, 5);

        $this->assertFalse($schema->isValid(-3));
        $this->assertTrue($schema->isValid(5));
    }

    public function testArray(): void
    {
        $v = new Validator();

        $schema = $v->array();

        $schema = $schema->required();

        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid([]));
        $this->assertTrue($schema->isValid(['hexlet']));

        $schema = $schema->sizeof(2);

        $this->assertFalse($schema->isValid(['hexlet']));
        $this->assertTrue($schema->isValid(['hexlet', 'code-basics']));
    }

    public function testShape(): void
    {
        $v = new Validator();

        $schema = $v->array();

        $schema = $schema->shape([
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

        $fn = fn($value, $start) => str_starts_with($value, $start);
        $v->addValidator('string', 'startWith', $fn);

        $schema = $v->string()->test('startWith', 'H');
        $this->assertFalse($schema->isValid('exlet'));
        $this->assertTrue($schema->isValid('Hexlet'));

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(6));
    }
}
