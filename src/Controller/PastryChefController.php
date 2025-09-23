<?php

namespace App\Controller;

use App\Entity\PastryChef;
use App\Form\PastryChefType;
use App\Repository\PastryChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pastry/chef')]
final class PastryChefController extends AbstractController
{
    #[Route(name: 'app_pastry_chef_index', methods: ['GET'])]
    public function index(PastryChefRepository $pastryChefRepository): Response
    {
        return $this->render('pastry_chef/index.html.twig', [
            'pastry_chefs' => $pastryChefRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pastry_chef_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pastryChef = new PastryChef();
        $form = $this->createForm(PastryChefType::class, $pastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pastryChef);
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pastry_chef/new.html.twig', [
            'pastry_chef' => $pastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_chef_show', methods: ['GET'])]
    public function show(PastryChef $pastryChef): Response
    {
        return $this->render('pastry_chef/show.html.twig', [
            'pastry_chef' => $pastryChef,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pastry_chef_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PastryChef $pastryChef, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PastryChefType::class, $pastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pastry_chef/edit.html.twig', [
            'pastry_chef' => $pastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_chef_delete', methods: ['POST'])]
    public function delete(Request $request, PastryChef $pastryChef, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pastryChef->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pastryChef);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
    }
}
