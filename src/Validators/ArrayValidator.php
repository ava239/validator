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
        }, 'valid', 'is_array');
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
                $errors = array_merge($errors, ["shape" => $message]);
                return false;
            }
            return array_reduce(
                array_keys($shape),
                function (bool $acc, $field) use ($data, $shape, &$errors, $message) {
                    if (!array_key_exists($field, $data)) {
                        $errors = array_merge($errors, ["shape" => $message]);
                        return false;
                    }
                    $result = $shape[$field]->isValid($data[$field]);
                    if (!$result) {
                        $errors = array_merge($errors, ["shape.{$field}" => $shape[$field]->getErrors()]);
                    }
                    return $acc && $result;
                },
                true
            );
        }, '');
        return $this;
    }
}
