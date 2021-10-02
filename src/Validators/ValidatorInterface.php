<?php

namespace Ava239\Validator\Validators;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param  mixed  $data
     * @return ValidatorInterface
     */
    public function validate($data): ValidatorInterface;
}
