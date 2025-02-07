<?php

declare(strict_types=1);

namespace Finaro\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Finaro\Entity\User\User;
use Finaro\Validator\Constraint\UniqueEmail;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new \LogicException('Invalid constraint type');
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $value]);

        if ($user) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
