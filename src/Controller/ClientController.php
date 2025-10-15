<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Message;
use App\Form\ClientType;
use App\Security\Voter\ClientVoter;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setRoles(["ROLE_CLIENT"]);
            $client->setPassword($userPasswordHasherInterface->hashPassword($client, $client->getPassword()));
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_show', ['id' => $client->getId()]);
        }
        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    #[IsGranted(ClientVoter::EDIT, subject: 'client')]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setIsProfileCompleted(true);
            $entityManager->persist($client);
            $entityManager->flush();
            return $this->redirectToRoute('app_client_show', ['id' => $client->getId()]);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    #[IsGranted(ClientVoter::DELETE, subject: 'client')]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {

        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            // Déconnexion utilisateur
            $tokenStorage->setToken(null);
            // vide message
            $messages = $entityManager->getRepository(Message::class)->findByClientRequests($client);
            // Supprime messages
            foreach ($messages as $message) {
                $entityManager->remove($message);
            }
            foreach ($client->getRequestOrders() as $requestOrder) {
                $entityManager->remove($requestOrder);
            }
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/comment-pastry', name: 'app_client_comment_pastry', methods: ['GET'])]
    public function commentPastry(Client $client): Response
    {
        //récupére commentaires pâtisseries pour ce client
        $commentPastry = $client->getCommentPastries();

        return $this->render('client/comment_pastry.html.twig', [
            'client' => $client,
            'comments' => $commentPastry,
        ]);
    }

    #[Route('/{id}/comment-chef', name: 'app_client_comment_chef', methods: ['GET'])]
    public function commentChef(Client $client): Response
    {
        //récupére commentaires chefs pour ce client
        $commentChef = $client->getCommentPastryChefs();

        return $this->render('client/comment_chef.html.twig', [
            'client' => $client,
            'comments' => $commentChef,
        ]);
    }
}
