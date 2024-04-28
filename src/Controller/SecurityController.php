<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Check if the user is authenticated
        if ($this->getUser()) {
            // Redirect based on user role
            switch ($this->getUser()->getRoles()[0]) {
                case 'ROLE_DOCTOR':
                    // Check if the user has a doctor entity
                    $doctor = $this->getUser()->getDoctor();
                    if ($doctor === null) {
                        return $this->redirectToRoute('app_doctor_new');
                    }
                    // Redirect to the route to show the doctor details
                    return $this->redirectToRoute('app_doctor_show', ['id' => $doctor->getId()]);

                case 'ROLE_SECRETARY':
                    // Check if the user has a secretary entity
                    $secretary = $this->getUser()->getSecretary();
                    if ($secretary === null) {
                        return $this->redirectToRoute('app_secretary_new');
                    }
                    // Redirect to the route to show the secretary details
                    return $this->redirectToRoute('app_secretary_show', ['id' => $secretary->getId()]);

                case 'ROLE_PATIENT':
                    // Check if the user has a patient entity
                    $patient = $this->getUser()->getPatient();
                    if ($patient === null) {
                        return $this->redirectToRoute('app_patient_new');
                    }
                    // Redirect to the route to show the patient details
                    return $this->redirectToRoute('app_patient_show', ['id' => $patient->getId()]);

                default:
                    return $this->redirectToRoute('app_logout');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // The logout action will be handled by Symfony's security system
        // and redirected to the login page based on the configuration in security.yaml.
    }
}
