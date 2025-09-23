<?php

namespace App\Controller;

use App\Entity\CommentPastryChef;
use App\Form\CommentPastryChefType;
use App\Repository\CommentPastryChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment/pastry/chef')]
final class CommentPastryChefController extends AbstractController
{
    #[Route(name: 'app_comment_pastry_chef_index', methods: ['GET'])]
    public function index(CommentPastryChefRepository $commentPastryChefRepository): Response
    {
        return $this->render('comment_pastry_chef/index.html.twig', [
            'comment_pastry_chefs' => $commentPastryChefRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comment_pastry_chef_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentPastryChef = new CommentPastryChef();
        $form = $this->createForm(CommentPastryChefType::class, $commentPastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentPastryChef);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment_pastry_chef/new.html.twig', [
            'comment_pastry_chef' => $commentPastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_pastry_chef_show', methods: ['GET'])]
    public function show(CommentPastryChef $commentPastryChef): Response
    {
        return $this->render('comment_pastry_chef/show.html.twig', [
            'comment_pastry_chef' => $commentPastryChef,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_pastry_chef_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommentPastryChef $commentPastryChef, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentPastryChefType::class, $commentPastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment_pastry_chef/edit.html.twig', [
            'comment_pastry_chef' => $commentPastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_pastry_chef_delete', methods: ['POST'])]
    public function delete(Request $request, CommentPastryChef $commentPastryChef, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentPastryChef->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentPastryChef);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
    }
}
