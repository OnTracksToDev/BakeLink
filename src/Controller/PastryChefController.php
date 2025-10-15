<?php

namespace App\Controller;

use App\Entity\Pastry;
use App\Entity\Message;
use App\Entity\PastryChef;
use App\Form\PastryChefType;
use App\Entity\CommentPastry;
use App\Entity\CommentPastryChef;
use App\Security\Voter\PastryChefVoter;
use App\Repository\PastryChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommentPastryChefRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/pastry/chef')]
class PastryChefController extends AbstractController
{
    #[Route('/', name: 'app_pastry_chef_index', methods: ['GET'])]
    public function index(PastryChefRepository $pastryChefRepository): Response
    {
        return $this->render('pastry_chef/index.html.twig', [
            'pastry_chefs' => $pastryChefRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pastry_chef_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response {
        $pastryChef = new PastryChef();
        $form = $this->createForm(PastryChefType::class, $pastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pastryChef->setRoles(["ROLE_CHEF"]);
            $pastryChef->setPassword($userPasswordHasherInterface->hashPassword($pastryChef, $pastryChef->getPassword()));
            $entityManager->persist($pastryChef);
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_chef_show', ['id' => $pastryChef->getId()]);
        }

        return $this->render('pastry_chef/new.html.twig', [
            'pastry_chef' => $pastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_chef_show', methods: ['GET'])]
    public function show(PastryChef $pastryChef, EntityManagerInterface $entityManager): Response
    {
        $commentPastryChef = $entityManager->getRepository(CommentPastryChef::class)
            ->findBy(['pastryChef' => $pastryChef], ['createdAt' => 'DESC']);

        return $this->render('pastry_chef/show.html.twig', [
            'pastry_chef' => $pastryChef,
            'commentPastryChef' => $commentPastryChef
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pastry_chef_edit', methods: ['GET', 'POST'])]
    #[IsGranted(PastryChefVoter::EDIT, subject: 'pastryChef')]
    public function edit(Request $request, PastryChef $pastryChef, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PastryChefType::class, $pastryChef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pastryChef->setIsProfileCompleted(true);
            $entityManager->persist($pastryChef);
            $entityManager->flush();


            return $this->redirectToRoute('app_pastry_chef_show', ['id' => $pastryChef->getId()]);
        }

        return $this->render('pastry_chef/edit.html.twig', [
            'pastry_chef' => $pastryChef,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_chef_delete', methods: ['POST'])]
    public function delete(Request $request, PastryChef $pastryChef, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pastryChef->getId(), $request->request->get('_token'))) {
            $tokenStorage->setToken(null);
            $entityManager->remove($pastryChef);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pastry/chef/{id}/comments', name: 'app_pastry_chef_pastry_comments', methods: ['GET'])]
    public function pastryComments(PastryChef $pastryChef, EntityManagerInterface $entityManager): Response
    {
        //récupére pâtisseries du chef pâtissier
        $pastries = $entityManager->getRepository(Pastry::class)->findBy(['pastryChef' => $pastryChef]);

        //récupére commentaires associés à pâtisserie
        $commentPastryChefs = [];
        foreach ($pastries as $pastry) {
            $comments = $entityManager->getRepository(CommentPastry::class)
                ->findBy(['pastry' => $pastry], ['createdAt' => 'DESC']);
            $commentPastryChefs = array_merge($commentPastryChefs, $comments);
        }

        return $this->render('pastry_chef/pastry_comments.html.twig', [
            'pastryChef' => $pastryChef,
            'commentPastryChefs' => $commentPastryChefs,
        ]);
    }
    #[Route('/{id}/comments', name: 'app_pastry_chef_comments', methods: ['GET'])]
    public function viewComments(PastryChef $pastryChef, CommentPastryChefRepository $commentPastryChefRepository): Response
    {
        $comments = $commentPastryChefRepository->findBy(['pastryChef' => $pastryChef], ['createdAt' => 'DESC']);

        return $this->render('pastry_chef/view_comments.html.twig', [
            'pastry_chef' => $pastryChef,
            'comments' => $comments,
        ]);
    }
}
