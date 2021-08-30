<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class StringValidator extends ValidatorBase implements ValidatorInterface
{
    /**
     * @var string
     */
    public $type = 'string';

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
        $this->addValidator(function ($data) {
            return is_string($data) || $data === null;
        });
    }

    public function contains(string $text): StringValidator
    {
        $this->addValidator(function ($data) use ($text) {
            return str_contains($data, $text);
        });
        return $this;
    }

    public function minLength(int $length): StringValidator
    {
        $this->addValidator(function ($data) use ($length) {
            return mb_strlen($data) >= $length;
        });
        return $this;
    }

    public function required(): StringValidator
    {
        $this->addValidator(function ($data) {
            return mb_strlen($data) > 0;
        });
        return $this;
    }
}
