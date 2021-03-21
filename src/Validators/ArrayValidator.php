<?php

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Validator;

class ArrayValidator extends ValidatorBase implements ValidatorInterface
{
    public string $type = 'array';

    public function __construct(Validator $parent)
    {
        parent::__construct($parent);
        $this->addValidator(fn($data) => is_array($data) || $data === null);
    }

    public function sizeof(int $size): ArrayValidator
    {
        $this->addValidator(fn($data) => count($data) >= $size);
        return $this;
    }

    public function required(): ArrayValidator
    {
        $this->addValidator(fn($data) => is_array($data));
        return $this;
    }

    /**
     * @param  ValidatorInterface[] $shape
     * @return ArrayValidator
     */
    public function shape($shape): ArrayValidator
    {
        $this->addValidator(fn($data) => array_reduce(
            array_keys($shape),
            fn($acc, $field) => $acc && $shape[$field]->isValid($data[$field]),
            true
        ));
        return $this;
    }
}
