<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class NumberValidator extends ValidatorBase implements ValidatorInterface
{
    /**
     * @var string
     */
    public $type = 'number';

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
        $this->addValidator(function ($data) {
            return is_integer($data) || $data === null;
        }, 'valid', 'is_number');
    }

    public function positive(string $message = null): NumberValidator
    {
        $this->addValidator(function ($data) {
            return !is_int($data) || $data > 0;
        }, 'is_positive', $message);
        return $this;
    }

    public function range(int $min, int $max, string $message = null): NumberValidator
    {
        $this->addValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        }, 'in_range', $message);
        return $this;
    }

    public function required(string $message = null): NumberValidator
    {
        $this->addValidator(function ($data) {
            return is_integer($data);
        }, 'required', $message);
        return $this;
    }
}
