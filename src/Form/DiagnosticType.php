<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiagnosticType extends AbstractType
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
{
    // Fetch symptoms from Flask API
    $response = $this->httpClient->request('GET', 'http://127.0.0.1:5000/symptoms');
    $symptoms = $response->toArray();

    // Define your form fields here
    $builder->add('symptoms', ChoiceType::class, [
        'choices' => $symptoms,
        'multiple' => true,
        'expanded' => false,
        'required' => false,
        'attr' => [
            'class' => 'form-control',
            'id' => 'countries', // Add the 'id' attribute here
            'multiple' => 'multiple'
        ],
    ]);
}


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
