<?php

namespace Hexlet\Validator;

use Hexlet\Validator\Validators\ArrayValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\StringValidator;
use Hexlet\Validator\Validators\ValidatorInterface;

class Validator
{
    public function make(string $type): ValidatorInterface
    {
        $typeName = ucfirst($type);
        $className = __NAMESPACE__ . "\\Validators\\{$typeName}Validator";
        return new $className();
    }

    public function string(): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->make('string');
        return $validator;
    }

    public function number(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->make('number');
        return $validator;
    }

    public function array(): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->make('array');
        return $validator;
    }
}
