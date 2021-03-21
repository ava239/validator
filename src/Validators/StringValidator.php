<?php

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Validator;

class StringValidator extends ValidatorBase implements ValidatorInterface
{
    public string $type = 'string';

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
        $this->addValidator(fn($data) => is_string($data) || $data === null);
    }

    public function contains(string $text): StringValidator
    {
        $this->addValidator(fn($data) => str_contains($data, $text));
        return $this;
    }

    public function minLength(int $length): StringValidator
    {
        $this->addValidator(fn($data) => mb_strlen($data) >= $length);
        return $this;
    }

    public function required(): StringValidator
    {
        $this->addValidator(fn($data) => mb_strlen($data) > 0);
        return $this;
    }
}
