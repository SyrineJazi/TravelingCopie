<?php

namespace App\Form;

use App\Entity\Reservation;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbrreservation')
            ->add('eventName')
            ->add('prixE')
            ->add('modepaiement', ChoiceType::class, [
                'label' => 'Mode de paiement',
                'choices' => [
                    'Carte bancaire' => 'carte_bancaire',
                    'PayPal' => 'paypal',
                    'Virement bancaire' => 'virement_bancaire'
                ]])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => false])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Normal' => 'normal',
                    'VIP' => 'vip',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
