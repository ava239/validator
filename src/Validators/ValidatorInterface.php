<?php

namespace Ava239\Validator\Validators;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;

    public function getErrors(): array;
}
