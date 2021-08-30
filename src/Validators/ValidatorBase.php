<?php

namespace Ava239\Validator\Validators;

use Closure;
use Ava239\Validator\Validator;

class ValidatorBase implements ValidatorInterface
{
    /** @var Validator $parent */
    protected $parent;
    /** @var array $validators */
    public $validators = [];
    /** @var string[] $errors */
    protected $errors = [];
    /** @var string $type */
    public $type;
    /** @var bool $valid */
    private $valid = true;

    public function addValidator(Closure $validator, string $name, string $message = null): void
    {
        $this->validators = array_merge($this->validators, [$name => [$validator, $message]]);
    }

    /**
     * @param  string  $name
     * @param  mixed  ...$params
     * @return ValidatorInterface
     */
    public function test(string $name, ...$params): ValidatorInterface
    {
        [$validator, $message] = $this->parent->getCustomValidator($this->type, $name);
        $this->addValidator(function ($data) use ($validator, $params) {
            return $validator($data, ...$params);
        }, $name, $message);
        return $this;
    }

    /**
     * @param  mixed  $data
     */
    public function validate($data): ValidatorInterface
    {
        $this->errors = [];
        $this->valid = array_reduce(
            array_keys($this->validators),
            function (bool $acc, string $key) use ($data) {
                [$fn, $message] = $this->validators[$key];
                $result = $fn($data, $this->errors);
                if (!$result) {
                    $this->errors = array_merge($this->errors, [$key => $message]);
                }
                return $acc && $result;
            },
            true
        );
        return $this;
    }

    /**
     * @param  mixed  $data
     * @return bool
     */
    public function isValid($data): bool
    {
        $this->validate($data);
        return $this->valid;
    }

    public function getErrors(): array
    {
        $errors = array_filter($this->errors);
        ksort($errors);
        return $errors;
    }
}
