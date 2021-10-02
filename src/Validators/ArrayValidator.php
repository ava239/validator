<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class ArrayValidator extends ValidatorBase implements ValidatorInterface
{
    /** @var string $type */
    public $type = 'array';

    public function __construct(Validator $parent, string $name = null)
    {
        parent::__construct($parent, $name);
        $this->addValidator(function ($data) {
            return is_array($data) || $data === null;
        }, 'valid', 'is_array', true);
    }

    public function sizeof(int $size, string $message = null): ArrayValidator
    {
        $this->addValidator(function ($data) use ($size) {
            return count($data) >= $size;
        }, 'sizeof', $message);
        return $this;
    }

    public function required(string $message = null): ArrayValidator
    {
        $this->addValidator(function ($data) {
            return is_array($data);
        }, 'required', $message);
        return $this;
    }

    /**
     * @param  ValidatorInterface[]  $shape
     * @return ArrayValidator
     */
    public function shape($shape, string $message = null): ArrayValidator
    {
        $this->addValidator(function ($data, &$errors) use ($shape, $message) {
            if (!is_array($data)) {
                $errors = $this->addErrors($errors, ["_" => $message]);
                return false;
            }
            return array_reduce(
                array_keys($shape),
                function (bool $acc, $field) use ($data, $shape, &$errors, $message) {
                    if (!array_key_exists($field, $data)) {
                        $errors = $this->addErrors($errors, ["_" => $message]);
                    }
                    $result = $shape[$field]->isValid($data[$field] ?? null);
                    if (!$result) {
                        $errors = $this->addErrors($errors, $shape[$field]->getErrors(), "{$field}.");
                    }
                    return $acc && $result;
                },
                true
            );
        }, '');
        return $this;
    }

    private function addErrors(array $oldErrors, array $errorList, string $prefix = ''): array
    {
        foreach ($errorList as $key => $val) {
            $oldErrors["{$prefix}{$key}"] = $val;
        }
        return $oldErrors;
    }
}
