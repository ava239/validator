<?php

namespace Hexlet\Validator\Validators;

use Closure;

class Validator implements ValidatorInterface
{

    protected string $type;
    protected array $validators = [];

    public function addValidator(Closure $validator): ValidatorInterface
    {
        $this->validators[] = $validator;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isValid($data): bool
    {
        return array_reduce($this->validators, function ($acc, $fn) use ($data) {
            return $acc && $fn($data);
        }, true);
    }

    public function required(): ValidatorInterface
    {
        return $this->addValidator(fn($data) => (bool) $data);
    }
}
