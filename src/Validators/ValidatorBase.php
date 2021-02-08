<?php

namespace Hexlet\Validator\Validators;

use Closure;
use Hexlet\Validator\Validator;

class ValidatorBase implements ValidatorInterface
{

    protected Validator $parent;
    protected string $type;
    protected array $validators = [];

    public function __construct(Validator $parent, array $validators = [])
    {
        $this->validators = $validators;
        $this->parent = $parent;
    }

    /**
     * @param  string  $name
     * @param  mixed  ...$params
     * @return ValidatorInterface
     */
    public function test(string $name, ...$params): ValidatorInterface
    {
        return $this->applyValidator(
            fn($data) => $this->parent->getCustomValidator($this->type, $name)($data, ...$params),
            $name
        );
    }

    protected function applyValidator(Closure $validator, string $name, bool $mutate = false): ValidatorInterface
    {
        $validators = $this->validators;
        $validators[$name] = $validator;
        if ($mutate) {
            $this->validators = $validators;
        }
        return new static($this->parent, $validators);
    }

    public function isValid($data): bool
    {
        return array_reduce($this->validators, function ($acc, $fn) use ($data): bool {
            return $acc && (bool) $fn($data);
        }, true);
    }
}
