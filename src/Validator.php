<?php

namespace Hexlet\Validator;

use Closure;
use Hexlet\Validator\Validators\ArrayValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\StringValidator;
use Hexlet\Validator\Validators\ValidatorInterface;

class Validator
{
    private array $customValidators = [];

    public function make(string $type): ValidatorInterface
    {
        $typeName = ucfirst($type);
        $className = __NAMESPACE__ . "\\Validators\\{$typeName}Validator";
        return new $className($this);
    }

    public function addValidator(string $type, string $name, Closure $validator): void
    {
        $this->customValidators[$type] = $this->customValidators[$type] ?? [];
        $this->customValidators[$type][$name] = $validator;
    }

    public function getCustomValidator(string $type, string $name): Closure
    {
        if (!array_key_exists($name, $this->customValidators[$type] ?? [])) {
            throw new \Exception("Custom method doesn't exist");
        }
        return $this->customValidators[$type][$name];
    }

    public function string(): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->make('string');
        return $validator;
    }

    public function number(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->make('number');
        return $validator;
    }

    public function array(): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->make('array');
        return $validator;
    }
}
