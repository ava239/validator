<?php

namespace Hexlet\Validator\Validators;

use Closure;

class StringValidator extends Validator implements ValidatorInterface
{
    public function contains(string $text): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->addValidator(function ($data) use ($text) {
            return str_contains($data, $text);
        });
        return $validator;
    }

    public function minLength(int $length): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->addValidator(function ($data) use ($length) {
            return mb_strlen($data) >= $length;
        });
        return $validator;
    }

    public function required(): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->addValidator(fn($data) => (bool) $data);
        return $validator;
    }
}
