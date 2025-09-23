<?php

namespace App\Controller;

use App\Entity\Pastry;
use App\Form\PastryType;
use App\Repository\PastryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pastry')]
final class PastryController extends AbstractController
{
    #[Route(name: 'app_pastry_index', methods: ['GET'])]
    public function index(PastryRepository $pastryRepository): Response
    {
        return $this->render('pastry/index.html.twig', [
            'pastries' => $pastryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pastry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pastry = new Pastry();
        $form = $this->createForm(PastryType::class, $pastry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pastry);
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pastry/new.html.twig', [
            'pastry' => $pastry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_show', methods: ['GET'])]
    public function show(Pastry $pastry): Response
    {
        return $this->render('pastry/show.html.twig', [
            'pastry' => $pastry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pastry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pastry $pastry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PastryType::class, $pastry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pastry/edit.html.twig', [
            'pastry' => $pastry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_delete', methods: ['POST'])]
    public function delete(Request $request, Pastry $pastry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pastry->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pastry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
    }
}
