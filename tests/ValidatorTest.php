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

        /** @var StringValidator $schema */
        $schema = $v->string();

        $this->assertTrue($schema->isValid(''));

        $schema->required();

        $this->assertTrue($schema->isValid('what does the fox say'));
        $this->assertTrue($schema->isValid('hexlet'));
        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(''));

        $schema->minLength(5);
        $this->assertFalse($schema->isValid('what'));
        $this->assertTrue($schema->isValid('what does'));

        $this->assertTrue($schema->contains('what')->isValid('what does the fox say'));
        $this->assertFalse($schema->contains('whatthe')->isValid('what does the fox say'));
    }

    public function testNumber(): void
    {
        $v = new Validator();

        /** @var NumberValidator $schema */
        $schema = $v->number();

        $this->assertTrue($schema->isValid(null));

        $schema->required();

        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(7));

        $this->assertTrue($schema->positive()->isValid(10));

        $schema->range(-5, 5);

        $this->assertFalse($schema->isValid(-3));
        $this->assertTrue($schema->isValid(5));
    }

    public function testArray(): void
    {
        $v = new Validator();

        /** @var ArrayValidator $schema */
        $schema = $v->array();

        /** @var ArrayValidator $schema */
        $schema = $schema->required();

        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid([]));
        $this->assertTrue($schema->isValid(['hexlet']));

        $schema->sizeof(2);

        $this->assertFalse($schema->isValid(['hexlet']));
        $this->assertTrue($schema->isValid(['hexlet', 'code-basics']));
    }
}
