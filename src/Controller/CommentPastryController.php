<?php

namespace App\Controller;

use App\Entity\CommentPastry;
use App\Form\CommentPastryType;
use App\Repository\CommentPastryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment/pastry')]
final class CommentPastryController extends AbstractController
{
    #[Route(name: 'app_comment_pastry_index', methods: ['GET'])]
    public function index(CommentPastryRepository $commentPastryRepository): Response
    {
        return $this->render('comment_pastry/index.html.twig', [
            'comment_pastries' => $commentPastryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comment_pastry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentPastry = new CommentPastry();
        $form = $this->createForm(CommentPastryType::class, $commentPastry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentPastry);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_pastry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment_pastry/new.html.twig', [
            'comment_pastry' => $commentPastry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_pastry_show', methods: ['GET'])]
    public function show(CommentPastry $commentPastry): Response
    {
        return $this->render('comment_pastry/show.html.twig', [
            'comment_pastry' => $commentPastry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_pastry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommentPastry $commentPastry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentPastryType::class, $commentPastry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_pastry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment_pastry/edit.html.twig', [
            'comment_pastry' => $commentPastry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_pastry_delete', methods: ['POST'])]
    public function delete(Request $request, CommentPastry $commentPastry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentPastry->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentPastry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_pastry_index', [], Response::HTTP_SEE_OTHER);
    }
}
