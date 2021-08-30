<?php

namespace Ava239\Validator\Validators;

use Closure;
use Ava239\Validator\Validator;

class ValidatorBase implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected $parent;
    /** @var Closure[] $validators */
    public $validators = [];
    /**
     * @var string
     */
    public $type;

    public function addValidator(Closure $validator): void
    {
        $this->validators = array_merge($this->validators, [$validator]);
    }

    /**
     * @param  string  $name
     * @param  mixed  ...$params
     * @return ValidatorInterface
     */
    public function test(string $name, ...$params): ValidatorInterface
    {
        $this->addValidator(function ($data) use ($name, $params) {
            return $this->parent->getCustomValidator($this->type, $name)($data, ...$params);
        });
        return $this;
    }

    /**
     * @param  mixed  $data
     * @return bool
     */
    public function isValid($data): bool
    {
        return array_reduce(
            $this->validators,
            function (bool $acc, Closure $fn) use ($data) {
                return $acc && $fn($data);
            },
            true
        );
    }
}
