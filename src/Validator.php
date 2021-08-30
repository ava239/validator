<?php

namespace Ava239\Validator;

use Closure;
use Ava239\Validator\Validators\ArrayValidator;
use Ava239\Validator\Validators\NumberValidator;
use Ava239\Validator\Validators\StringValidator;
use Ava239\Validator\Validators\ValidatorInterface;

class Validator
{
    /**
     * @var array
     */
    private $customValidatorFns = [];

    public function make(string $type): ValidatorInterface
    {
        $typeName = ucfirst($type);
        $className = __NAMESPACE__ . "\\Validators\\{$typeName}Validator";
        return new $className($this);
    }

    public function addValidator(string $type, string $name, Closure $validator): void
    {
        $this->customValidatorFns[$type] = $this->customValidatorFns[$type] ?? [];
        $this->customValidatorFns[$type][$name] = $validator;
    }

    public function getCustomValidator(string $type, string $name): Closure
    {
        if (!array_key_exists($name, $this->customValidatorFns[$type] ?? [])) {
            throw new \Exception("Custom method doesn't exist");
        }
        return $this->customValidatorFns[$type][$name];
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
