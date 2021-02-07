<?php

namespace Hexlet\Validator\Validators;

use Closure;

class ArrayValidator extends Validator implements ValidatorInterface
{
    public function sizeof(int $size): ValidatorInterface
    {
        return $this->addValidator(function ($data) use ($size) {
            return count($data) >= $size;
        });
    }

    public function required(): ValidatorInterface
    {
        return $this->addValidator(fn($data) => is_array($data));
    }
}
