<?php

namespace App\Form;

use App\Model\FormData;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        try {
            $placeholder = '--- Please select ---';
            $symbolOptions = $options['companyOptions']->getOptions();
        } catch (Exception) {
            $symbolOptions = [];
            $placeholder = '--- There is an issue about fetching companies ---';
        }

        $builder
            ->add('symbol', ChoiceType::class, [
                'choices' => $symbolOptions,
                'placeholder' => $placeholder,
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'date-fields'],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'date-fields'],
            ])
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => FormData::class]);
        $resolver->setRequired('companyOptions');
    }
}
