<?php

namespace App\Form;

use App\Entity\Event;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as TypeDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;
use Symfony\Component\Validator\Constraints\Positive;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('capacity')
            ->add('reserved') 
            ->add('date', TypeDateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('end', TypeDateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('numberofdays')
            ->add('prix')
            ->add('destination')
            ->add('all_day')
            ->add('background_color', ColorType::class)
        ->add('border_color', ColorType::class)
        ->add('text_color', ColorType::class)
            ->add('image_file',FileType::class,[
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new ConstraintsFile([
                        'maxSize' => '2Mi',
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
