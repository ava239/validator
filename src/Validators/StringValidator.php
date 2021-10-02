<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class StringValidator extends ValidatorBase implements ValidatorInterface
{
    /** @var string */
    public $type = 'string';

    public function __construct(Validator $parent, string $name = null)
    {
        parent::__construct($parent, $name);
        $this->addValidator(function ($data) {
            return is_string($data) || $data === null;
        }, 'valid', 'is_string', true);
    }

    public function contains(string $text, string $message = null): StringValidator
    {
        $this->addValidator(function ($data) use ($text) {
            return str_contains($data, $text);
        }, 'contains', $message);
        return $this;
    }

    public function minLength(int $length, string $message = null): StringValidator
    {
        $this->addValidator(function ($data) use ($length) {
            return mb_strlen($data) >= $length;
        }, 'minLength', $message);
        return $this;
    }

    public function maxLength(int $length, string $message = null): StringValidator
    {
        $this->addValidator(function ($data) use ($length) {
            return mb_strlen($data) <= $length;
        }, 'maxLength', $message);
        return $this;
    }

    public function required(string $message = null): StringValidator
    {
        $this->addValidator(function ($data) {
            return mb_strlen($data) > 0;
        }, 'required', $message);
        return $this;
    }
}
