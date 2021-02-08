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

    public function make(string $type, array $validators = []): ValidatorInterface
    {
        $typeName = ucfirst($type);
        $className = __NAMESPACE__ . "\\Validators\\{$typeName}Validator";
        return new $className($this, $validators);
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
        $validator = $this->make('string', [fn($data) => is_string($data) || $data === null]);
        return $validator;
    }

    public function number(): NumberValidator
    {
        /** @var NumberValidator $validator */
        $validator = $this->make('number', [fn($data) => is_integer($data) || $data === null]);
        return $validator;
    }

    public function array(): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->make('array', [fn($data) => is_array($data) || $data === null]);
        return $validator;
    }
}
