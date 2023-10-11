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
        /** @var ValidatorInterface */
        return new $className($this, $name);
    }

    /**
     * @param  string  $type
     * @param  string  $name
     * @param  Closure  $validator
     * @param  string|null  $message
     */
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

    /**
     * @param  string|null  $name
     * @return StringValidator
     */
    public function string(string $name = null): StringValidator
    {
        /** @var StringValidator $validator */
        $validator = $this->make('string', $name);
        return $validator;
    }

    /**
     * @param  string|null  $name
     * @return NumberValidator
     */
    public function number(string $name = null): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->make('number', $name);
        return $validator;
    }

    /**
     * @param  string|null  $name
     * @return ArrayValidator
     */
    public function array(string $name = null): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->make('array', $name);
        return $validator;
    }
}
