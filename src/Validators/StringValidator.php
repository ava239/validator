<?php

namespace Hexlet\Validator\Validators;

use Closure;

class StringValidator extends Validator implements ValidatorInterface
{
    protected string $type = 'string';

    public function contains(string $text): ValidatorInterface
    {
        return $this->addValidator(function ($data) use ($text) {
            return str_contains($data, $text);
        });
    }

    public function minLength(int $length): ValidatorInterface
    {
        return $this->addValidator(function ($data) use ($length) {
            return mb_strlen($data) >= $length;
        });
    }
}
