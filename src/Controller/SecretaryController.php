<?php

namespace App\Controller;

use App\Entity\Secretary;
use App\Form\SecretaryType;
use App\Repository\SecretaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/secretary')]
class SecretaryController extends AbstractController
{
    #[Route('/', name: 'app_secretary_index', methods: ['GET'])]
    public function index(SecretaryRepository $secretaryRepository): Response
    {
        return $this->render('secretary/index.html.twig', [
            'secretaries' => $secretaryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_secretary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $secretary = new Secretary();
        $secretary->setUser($this->getUser()); // Set the user for the secretary
        $form = $this->createForm(SecretaryType::class, $secretary);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($secretary);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('secretary/new.html.twig', [
            'secretary' => $secretary,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_secretary_show', methods: ['GET'])]
    public function show(Secretary $secretary): Response
    {
        return $this->render('secretary/show.html.twig', [
            'secretary' => $secretary,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_secretary_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Secretary $secretary, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecretaryType::class, $secretary);
        $secretary->setUser( $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_secretary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('secretary/edit.html.twig', [
            'secretary' => $secretary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_secretary_delete', methods: ['POST'])]
    public function delete(Request $request, Secretary $secretary, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secretary->getId(), $request->request->get('_token'))) {
            $entityManager->remove($secretary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_secretary_index', [], Response::HTTP_SEE_OTHER);
    }
}
