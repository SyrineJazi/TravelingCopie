<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                // Add any additional options you need here
                'html5' => false, // Disable HTML5 date input to ensure consistent behavior across browsers
                'attr' => ['class' => 'datepicker'], // Add a class for targeting with JavaScript
                // Optionally, you can add 'format' option to specify the desired date format
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                  'Extra'  => 'Extra',
                  'Incluse'  => 'Incluse',
                ],
                'expanded' => true, // radio buttons
                'multiple' => false, // Only one option
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
