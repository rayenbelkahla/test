<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Appointment;
use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('hour')
            ->add('patientStatus',ChoiceType::class, [
                'choices' => [
                    'Urgent' => 1,
                    'Pas urgent' => 0,
                ],
                'expanded' => false, // Change to false to render as select box
                'required' => true,
            ])
            ->add('progress')
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
'choice_label' => 'id',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
'choice_label' => 'id',
            ])
            ->add('Consultation', EntityType::class, [
                'class' => Consultation::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
