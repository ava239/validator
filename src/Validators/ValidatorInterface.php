<?php

namespace Hexlet\Validator\Validators;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;
}
