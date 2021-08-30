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
        });
    }

    public function positive(): NumberValidator
    {
        $this->addValidator(function ($data) {
            return !is_int($data) || $data > 0;
        });
        return $this;
    }

    public function range(int $min, int $max): NumberValidator
    {
        $this->addValidator(function ($data) use ($min, $max) {
            return $data >= $min && $data <= $max;
        });
        return $this;
    }

    public function required(): NumberValidator
    {
        $this->addValidator(function ($data) {
            return is_integer($data);
        });
        return $this;
    }
}
