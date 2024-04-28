<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class InfoController extends AbstractController
{
    #[Route('/', name: 'app_homePage')]
    public function index(): Response
    {
        return $this->render('info/homePage.html.twig');
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('info/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('info/contact.html.twig');
    }

    #[Route('/thankYouPage', name: 'app_thankYouPage')]
    public function thankYouPage(): Response
    {
        return $this->render('info/thankYouPage.html.twig');
    }
}
