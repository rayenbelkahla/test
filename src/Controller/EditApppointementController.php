<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditApppointementController extends AbstractController
{
    #[Route('/edit/apppointement', name: 'app_edit_apppointement')]
    public function index(): Response
    {
        return $this->render('edit_apppointement/index.html.twig', [
            'controller_name' => 'EditApppointementController',
        ]);
    }
}
