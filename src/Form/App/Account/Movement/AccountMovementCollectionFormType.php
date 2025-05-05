<?php

declare(strict_types=1);

namespace App\Form\App\Account\Movement;

use App\DataEntity\App\Account\Movement\AccountMovementCollectionData;
use App\Form\App\Account\Movement\AccountMovementFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{CollectionType, NumberType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountMovementCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('collection', CollectionType::class, [
                'entry_type' => AccountMovementFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountMovementCollectionData::class,
            'csrf_protection' => false,
        ]);
    }
}