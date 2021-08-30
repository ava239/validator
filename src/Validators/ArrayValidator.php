<?php

namespace Ava239\Validator\Validators;

use Ava239\Validator\Validator;

class ArrayValidator extends ValidatorBase implements ValidatorInterface
{
    /**
     * @var string
     */
    public $type = 'array';

    public function __construct(Validator $parent)
    {
        $this->parent = $parent;
        $this->addValidator(function ($data) {
            return is_array($data) || $data === null;
        });
    }

    public function sizeof(int $size): ArrayValidator
    {
        $this->addValidator(function ($data) use ($size) {
            return count($data) >= $size;
        });
        return $this;
    }

    public function required(): ArrayValidator
    {
        $this->addValidator(function ($data) {
            return is_array($data);
        });
        return $this;
    }

    /**
     * @param  ValidatorInterface[]  $shape
     * @return ArrayValidator
     */
    public function shape($shape): ArrayValidator
    {
        $this->addValidator(function ($data) use ($shape) {
            return array_reduce(
                array_keys($shape),
                function (bool $acc, $field) use ($data, $shape) {
                    return $acc && $shape[$field]->isValid($data[$field]);
                },
                true
            );
        });
        return $this;
    }
}
