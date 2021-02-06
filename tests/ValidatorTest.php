<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
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
}
