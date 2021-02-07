<?php

namespace Hexlet\Validator\Validators;

use Closure;

class ArrayValidator extends Validator implements ValidatorInterface
{
    public function sizeof(int $size): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->addValidator(function ($data) use ($size) {
            return count($data) >= $size;
        });
        return $validator;
    }

    public function required(): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->addValidator(fn($data) => is_array($data));
        return $validator;
    }

    /**
     * @param  ValidatorInterface[]  $shape
     * @return ArrayValidator
     */
    public function shape(array $shape): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->addValidator(fn($data) => array_reduce(
            array_keys($shape),
            fn($acc, $field) => $acc && $shape[$field]->isValid($data[$field]),
            true
        ));
        return $validator;
    }
}
