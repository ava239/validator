<?php

namespace Hexlet\Validator\Validators;

use Closure;
use Hexlet\Validator\Validator;

class ValidatorBase implements ValidatorInterface
{
    protected Validator $parent;
    /** @var Closure[] $validators */
    public $validators = [];
    public string $type;

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
    }

    public function addValidator(Closure $validator): void
    {
        $this->validators = [...$this->validators, $validator];
    }

    /**
     * @param  string  $name
     * @param  mixed  ...$params
     * @return ValidatorInterface
     */
    public function test(string $name, ...$params): ValidatorInterface
    {
        $this->addValidator(fn($data) => $this->parent->getCustomValidator($this->type, $name)($data, ...$params));
        return $this;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool
    {
        return array_reduce(
            $this->validators,
            fn($acc, Closure $fn) => $acc && (bool) $fn($data),
            true
        );
    }
}
