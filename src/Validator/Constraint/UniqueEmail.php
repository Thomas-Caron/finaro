<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Validator\UniqueEmailValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class UniqueEmail extends Constraint
{
    public $message = 'Cet email est déjà utilisé.';

    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}