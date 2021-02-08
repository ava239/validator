<?php

namespace Hexlet\Validator\Validators;

class ArrayValidator extends ValidatorBase implements ValidatorInterface
{
    protected string $type = 'array';

    public function sizeof(int $size): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->applyValidator(function ($data) use ($size) {
            return count($data) >= $size;
        }, 'sizeof', true);
        return $validator;
    }

    public function required(): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->applyValidator(fn($data) => is_array($data), 'required', true);
        return $validator;
    }

    /**
     * @param  ValidatorInterface[]  $shape
     * @return ArrayValidator
     */
    public function shape(array $shape): ArrayValidator
    {
        /** @var ArrayValidator $validator */
        $validator = $this->applyValidator(fn($data) => array_reduce(
            array_keys($shape),
            fn($acc, $field) => $acc && $shape[$field]->isValid($data[$field]),
            true
        ), 'shape', true);
        return $validator;
    }
}
