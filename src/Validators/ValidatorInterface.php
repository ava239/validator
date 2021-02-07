<?php

namespace Hexlet\Validator\Validators;

use Closure;

interface ValidatorInterface
{
    public function __construct(array $validators = []);
    public function addValidator(Closure $validator): ValidatorInterface;

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid($data): bool;
}
