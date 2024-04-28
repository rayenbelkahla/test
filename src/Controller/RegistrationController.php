<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]); // Ensure $roles is wrapped in an array

            $firstName = $form->get('firstName')->getData();
            $user->setFirstName($firstName);

            $lastName = $form->get('lastName')->getData();
            $user->setLastName($lastName);

            $phone = $form->get('phone')->getData();
            $user->setPhone($phone);

            $gender = $form->get('gender')->getData();
            $user->setGender($gender);

            // Your controller
            $birthdate = $form->get('birthdate')->getData();

            // Convert the selected birthdate to a Unix timestamp
            $birthdateTimestamp = $birthdate->getTimestamp();

            $user->setBirthdate($birthdateTimestamp);

            $timestamp = time();
            $user->setCreationDate($timestamp);
            
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            // Redirect based on user role
            return $this->redirectToRoute('app_login');
            // switch ($roles) {
            //     case 'ROLE_DOCTOR':
            //         return $this->redirectToRoute('app_doctor_new');
            //     case 'ROLE_SECRETARY':
            //         return $this->redirectToRoute('app_secretary_new');
            //     case 'ROLE_PATIENT':
            //         return $this->redirectToRoute('app_patient_new');
            //     default:
            //         return $this->redirectToRoute('_profiler_home');
            // }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
