<?php

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Validator;

interface ValidatorInterface
{
    public function __construct(Validator $parent);

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;
}
