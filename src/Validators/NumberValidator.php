<?php

namespace Hexlet\Validator\Validators;

use Closure;

class NumberValidator extends Validator implements ValidatorInterface
{
    public function positive(): ValidatorInterface
    {
        return $this->addValidator(function ($data) {
            return $data >= 0;
        });
    }

    public function range(int $min, int $max): ValidatorInterface
    {
        return $this->addValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        });
    }

    public function required(): ValidatorInterface
    {
        return $this->addValidator(fn($data) => is_integer($data));
    }
}
