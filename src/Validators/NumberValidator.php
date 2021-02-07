<?php

namespace Hexlet\Validator\Validators;

use Closure;

class NumberValidator extends Validator implements ValidatorInterface
{
    public function positive(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->addValidator(function ($data) {
            return $data >= 0;
        });
        return $validator;
    }

    public function range(int $min, int $max): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->addValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        });
        return $validator;
    }

    public function required(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->addValidator(fn($data) => is_integer($data));
        return $validator;
    }
}
