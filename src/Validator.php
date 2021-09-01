<?php

namespace Ava239\Validator;

use Closure;
use Ava239\Validator\Validators\ArrayValidator;
use Ava239\Validator\Validators\NumberValidator;
use Ava239\Validator\Validators\StringValidator;
use Ava239\Validator\Validators\ValidatorInterface;

class Validator
{
    /** @var array */
    private $customValidatorFns = [];

    public function make(string $type, string $name = null): ValidatorInterface
    {
        $typeName = ucfirst($type);
        $className = __NAMESPACE__ . "\\Validators\\{$typeName}Validator";
        return new $className($this, $name);
    }

    public function addValidator(string $type, string $name, Closure $validator, string $message = null): void
    {
        $this->customValidatorFns[$type] = $this->customValidatorFns[$type] ?? [];
        $this->customValidatorFns[$type][$name] = [$validator, $message];
    }

    public function getCustomValidator(string $type, string $name): array
    {
        if (!array_key_exists($name, $this->customValidatorFns[$type] ?? [])) {
            throw new \Exception("Custom method doesn't exist");
        }
        return $this->customValidatorFns[$type][$name];
    }

    public function string(string $name = null): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->make('string', $name);
        return $validator;
    }

    public function number(string $name = null): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->make('number', $name);
        return $validator;
    }

    public function array(string $name = null): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->make('array', $name);
        return $validator;
    }
}
