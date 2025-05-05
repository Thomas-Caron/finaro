<?php

declare(strict_types=1);

namespace App\Services\Helper;

use Symfony\Component\Form\FormInterface;

class FormHelper
{
    public static function getErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = [];

                foreach ($child->getErrors(true) as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}