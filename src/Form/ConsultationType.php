<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Consultation;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Prescription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            ->add('Prescription', EntityType::class, [
                'class' => Prescription::class,
'choice_label' => 'id',
            ])
            ->add('appointment', EntityType::class, [
                'class' => Appointment::class,
'choice_label' => 'id',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
'choice_label' => 'id',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
