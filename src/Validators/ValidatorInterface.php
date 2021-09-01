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
     * @param  bool  $flat
     * @return array
     */
    public function getErrors(bool $flat = false): array;

    /**
     * @param  mixed  $data
     * @return ValidatorInterface
     */
    public function validate($data): ValidatorInterface;
}
