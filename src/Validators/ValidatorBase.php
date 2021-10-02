<?php

namespace Ava239\Validator\Validators;

use Closure;
use Ava239\Validator\Validator;

class ValidatorBase implements ValidatorInterface
{
    /** @var Validator $parent */
    protected $parent;
    /** @var string|null $fieldName */
    protected $fieldName = null;
    /** @var array $validators */
    public $validators = [];
    /** @var string[] $errors */
    protected $errors = [];
    /** @var string $type */
    public $type;
    /** @var bool $valid */
    private $valid = true;

    public function __construct(Validator $parent, string $name = null)
    {
        $this->parent = $parent;
        $this->fieldName = $name;
    }

    public function addValidator(Closure $validator, string $name, string $message = null, bool $isFinal = false): void
    {
        $this->validators = array_merge($this->validators, [$name => [$validator, $message, $isFinal]]);
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
        [$this->valid] = array_reduce(
            array_keys($this->validators),
            function (array $acc, string $key) use ($data) {
                [$valid, $final] = $acc;
                if ($final) {
                    return $acc;
                }
                [$fn, $message, $isFinal] = $this->validators[$key];
                $result = $fn($data, $this->errors);
                if (!$result) {
                    $this->errors = array_merge($this->errors, [$key => $message]);
                    if ($isFinal) {
                        $final = true;
                    }
                }
                return [$valid && $result, $final];
            },
            [true, false]
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
        return $this->arrayMapRecursive(function ($element) {
            if (!is_string($element)) {
                return $element;
            }
            return preg_replace('~{{name}}~', $this->fieldName ?? '', $element);
        }, $errors);
    }

    private function arrayMapRecursive(Closure $callback, array $array): array
    {
        $output = [];
        foreach ($array as $key => $data) {
            if (is_array($data)) {
                $output[$key] = self::arrayMapRecursive($callback, $data);
            } else {
                $output[$key] = $callback($data);
            }
        }
        return $output;
    }
}
