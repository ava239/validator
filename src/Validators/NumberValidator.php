<?php

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Validator;

class NumberValidator extends ValidatorBase implements ValidatorInterface
{
    public string $type = 'number';

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
        $this->addValidator(fn($data) => is_integer($data) || $data === null);
    }

    public function positive(): NumberValidator
    {
        $this->addValidator(fn($data) => !is_int($data) || $data > 0);
        return $this;
    }

    public function range(int $min, int $max): NumberValidator
    {
        $this->addValidator(fn($data) => $data >= $min && $data <= $max);
        return $this;
    }

    public function required(): NumberValidator
    {
        $this->addValidator(fn($data) => is_integer($data));
        return $this;
    }
}
