<?php

namespace Hexlet\Validator\Validators;

use Closure;

interface ValidatorInterface
{
    public function addValidator(Closure $validator): ValidatorInterface;
    public function getType(): string;

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;
}
