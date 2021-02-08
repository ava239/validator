<?php

namespace Hexlet\Validator\Validators;

class StringValidator extends ValidatorBase implements ValidatorInterface
{
    protected string $type = 'string';

    public function contains(string $text): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($text): bool {
            return str_contains($data, $text);
        }, 'contains');
        return $validator;
    }

    public function minLength(int $length): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($length): bool {
            return mb_strlen($data) >= $length;
        }, 'minLength');
        return $validator;
    }

    public function required(): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->applyValidator(fn($data) => mb_strlen($data) > 0, 'required', true);
        return $validator;
    }
}
