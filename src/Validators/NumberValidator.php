<?php

namespace Hexlet\Validator\Validators;

class NumberValidator extends ValidatorBase implements ValidatorInterface
{
    protected string $type = 'number';

    public function positive(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->applyValidator(function ($data) {
            return $data >= 0;
        });
        return $validator;
    }

    public function range(int $min, int $max): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        });
        return $validator;
    }

    public function required(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->applyValidator(fn($data) => is_integer($data), true);
        return $validator;
    }
}
