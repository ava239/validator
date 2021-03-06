<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class NumberValidator extends ValidatorBase implements ValidatorInterface
{
    /** @var string */
    public $type = 'number';

    public function __construct(Validator $parent, string $name = null)
    {
        parent::__construct($parent, $name);
        $this->addValidator(function ($data) {
            return is_integer($data) || $data === null;
        }, 'valid', 'is_number', true);
    }

    public function positive(string $message = null): NumberValidator
    {
        $this->addValidator(function ($data) {
            return !is_int($data) || $data > 0;
        }, 'positive', $message);
        return $this;
    }

    public function range(int $min, int $max, string $message = null): NumberValidator
    {
        $this->addValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        }, 'range', $message);
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
