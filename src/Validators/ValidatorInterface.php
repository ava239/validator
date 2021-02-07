<?php

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Validator;

interface ValidatorInterface
{
    public function __construct(Validator $parent, array $validators = []);

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;
}
