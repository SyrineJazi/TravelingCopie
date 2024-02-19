<?php

namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('destination', ChoiceType::class, [
                'choices' => [
                    'Tunisie' => 'Tunisie',
                    'United States' => 'United States',
                    'Canada' => 'Canada',
                    'Mexico' => 'Mexico',
                    'Brazil' => 'Brazil',
                    'Argentina' => 'Argentina',
                    'United Kingdom' => 'United Kingdom',
                    'Germany' => 'Germany',
                    'France' => 'France',
                    'Italy' => 'Italy',
                    'Spain' => 'Spain',
                    'Russia' => 'Russia',
                    'China' => 'China',
                    'Japan' => 'Japan',
                    'South Korea' => 'South Korea',
                    'India' => 'India',
                    'Australia' => 'Australia',
                    'South Africa' => 'South Africa',
                    'Egypt' => 'Egypt',
                    'Nigeria' => 'Nigeria',
                    'Kenya' => 'Kenya',
                    'Ethiopia' => 'Ethiopia',
                    'Saudi Arabia' => 'Saudi Arabia',
                    'United Arab Emirates' => 'United Arab Emirates',
                    'Qatar' => 'Qatar',
                    'Kuwait' => 'Kuwait',
                    'Turkey' => 'Turkey',
                    'Greece' => 'Greece',
                    'Switzerland' => 'Switzerland',
                    'Sweden' => 'Sweden',
                    'Norway' => 'Norway',
                    'Denmark' => 'Denmark',
                    'Finland' => 'Finland',
                    'Netherlands' => 'Netherlands',
                    'Belgium' => 'Belgium',
                    'Portugal' => 'Portugal',
                    'Ireland' => 'Ireland',
                    'Austria' => 'Austria',
                    'Hungary' => 'Hungary',
                    'Czech Republic' => 'Czech Republic',
                    'Poland' => 'Poland',
                    'Slovakia' => 'Slovakia',
                    'Romania' => 'Romania',
                    'Bulgaria' => 'Bulgaria',
                    'Ukraine' => 'Ukraine',
                    'Iran' => 'Iran',
                    'Pakistan' => 'Pakistan',
                    'Bangladesh' => 'Bangladesh',
                    'Indonesia' => 'Indonesia',         
                ],
            ])
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                  'Voyage Touristique'  => 'Voyage Touristique',
                  'Voyage Humanitaire'  => 'Voyage Humanitaire',
                ],
                'expanded' => true, // radio buttons
                'multiple' => false, // Only one option
            ])
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                // Add any additional options you need here
                'html5' => false, // Disable HTML5 date input to ensure consistent behavior across browsers
                'attr' => ['class' => 'datepicker'], // Add a class for targeting with JavaScript
                // Optionally, you can add 'format' option to specify the desired date format
            ])
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                // Add any additional options you need here
                'html5' => false, // Disable HTML5 date input to ensure consistent behavior across browsers
                'attr' => ['class' => 'datepicker'], // Add a class for targeting with JavaScript
                // Optionally, you can add 'format' option to specify the desired date format
            ])
            ->add('image1', FileType::class,[
                'label'=>'Portrait',
                'required'=>false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
