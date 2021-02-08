<?php

namespace Hexlet\Validator\Validators;

class StringValidator extends ValidatorBase implements ValidatorInterface
{
    protected string $type = 'string';

    public function contains(string $text): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($text) {
            return str_contains($data, $text);
        });
        return $validator;
    }

    public function minLength(int $length): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($length) {
            return mb_strlen($data) >= $length;
        });
        return $validator;
    }

    public function required(): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(fn($data) => mb_strlen($data) > 0, true);
        return $validator;
    }
}
