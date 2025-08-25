<?php

declare(strict_types=1);

namespace App\Form\App\Label;

use App\DataEntity\App\Label\LabelCollectionData;
use App\Form\App\Label\LabelFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('collection', CollectionType::class, [
                'entry_type' => LabelFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LabelCollectionData::class,
            'csrf_protection' => false,
        ]);
    }
}