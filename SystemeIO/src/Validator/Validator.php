<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{

    public function __construct(protected ValidatorInterface $validator)
    {
    }

    public function validate(mixed $obj): ?string
    {
        $errors = $this->validator->validate($obj);
        if (count($errors) > 0) {
            return (string)$errors;
        }
        return null;
    }

    public function validateWithException(mixed $obj): void
    {
        $errors = $this->validator->validate($obj);
        if (count($errors) > 0) {
            throw new BadRequestException($errors->__toString());
        }
    }

}