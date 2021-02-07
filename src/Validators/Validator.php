<?php

namespace Hexlet\Validator\Validators;

use Closure;

class Validator implements ValidatorInterface
{

    protected array $validators = [];

    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    public function addValidator(Closure $validator): ValidatorInterface
    {
        $validators = [...$this->validators, $validator];
        return new static($validators);
    }

    public function isValid($data): bool
    {
        return array_reduce($this->validators, function ($acc, $fn) use ($data) {
            return $acc && $fn($data);
        }, true);
    }
}
