<?php

namespace App\Controller;

use App\Entity\CommentPastryChef;
use App\Form\CommentPastryChefType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Voter\CommentPastryChefVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CommentPastryChefRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment/pastry/chef')]
class CommentPastryChefController extends AbstractController
{
    #[Route('/', name: 'app_comment_pastry_chef_index', methods: ['GET'])]
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

            //récupére ID pâtissier associé au commentaire
            $pastryChefId = $commentPastryChef->getPastryChef()->getId();

            //redirection vers la page de profil du pâtissier
            return $this->redirectToRoute('app_pastry_chef_show', ['id' => $pastryChefId], Response::HTTP_SEE_OTHER);
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
    #[IsGranted(CommentPastryChefVoter::EDIT, subject: 'commentPastryChef')]
    public function edit(Request $request, CommentPastryChef $commentPastryChef, EntityManagerInterface $entityManager): Response
    {
        //récupére pastryChef lié au commentaire
        $pastrychef = $commentPastryChef->getPastryChef();
        $form = $this->createForm(CommentPastryChefType::class, $commentPastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            //id client
            $clientId = $commentPastryChef->getClient()->getId();

            //redirection
            return $this->redirectToRoute('app_client_show', ['id' => $clientId]);
        }

        return $this->render('comment_pastry_chef/edit.html.twig', [
            'comment_pastry_chef' => $commentPastryChef,
            'pastrychef' => $pastrychef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_pastry_chef_delete', methods: ['POST'])]
    public function delete(Request $request, CommentPastryChef $commentPastryChef, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentPastryChef->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentPastryChef);
            $entityManager->flush();

            //id client
            $clientId = $commentPastryChef->getClient()->getId();

            //redirection
            return $this->redirectToRoute('app_client_show', ['id' => $clientId]);
        }


        return $this->redirectToRoute('app_comment_pastry_chef_index', [], Response::HTTP_SEE_OTHER);
    }
}
