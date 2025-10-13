<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\PastryChef;
use App\Entity\RequestOrder;
use App\Form\RequestOrderType;
use App\Service\RequestOrderService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestOrderRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/request/order')]
class RequestOrderController extends AbstractController
{
    #[Route('/', name: 'app_request_order_index', methods: ['GET'])]
    public function index(RequestOrderRepository $requestOrderRepository): Response
    {
        return $this->render('request_order/index.html.twig', [
            'request_orders' => $requestOrderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_request_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestOrder = new RequestOrder();
        $form = $this->createForm(RequestOrderType::class, $requestOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requestOrder);
            $entityManager->flush();

            return $this->redirectToRoute('app_request_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('request_order/new.html.twig', [
            'request_order' => $requestOrder,
            'form' => $form,
        ]);
    }


    #[Route('/new/{pastryChefId}', name: 'app_request_order_new_pastrychef', methods: ['GET', 'POST'])]
    public function newFromPastryChef(Request $request, EntityManagerInterface $entityManager, RequestOrderService $requestOrderService, Security $security, ?int $pastryChefId = null): Response
    {

        $pastryChef = $entityManager->getRepository(PastryChef::class)->find($pastryChefId);
        //si patissier n'existe pas
        if (!$pastryChef) {
            return $this->redirectToRoute('app_home');
        }
        //Request Order Service
        $requestOrder = $requestOrderService->createFromPastryChef($pastryChef, $security);

        $form = $this->createForm(RequestOrderType::class, $requestOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requestOrder);
            $entityManager->flush();
            $client = $security->getUser();

            if ($client instanceof Client) {
                return $this->redirectToRoute('app_view_client_requests', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_home');
            }
        }



        return $this->render('request_order/new.html.twig', [
            'request_order' => $requestOrder,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_request_order_show', methods: ['GET', 'POST'])]
    public function show(RequestOrder $requestOrder, EntityManagerInterface $entityManager, Request $request): Response
    {
        $message = new Message();
        $message->setRequestOrder($requestOrder);
        
        /** @var UserInterface $user */
        $user = $this->getUser();
        $sender = $user->getId();
        $message->setSender($sender);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            //réinitialise message pour effacer le contenu du formulaire
            $message = new Message();
            $message->setRequestOrder($requestOrder);
            $message->setSender($sender);
            $form = $this->createForm(MessageType::class, $message);
        }
        //tous les messages de la requestOrder
        $messages = $entityManager->getRepository(Message::class)->findBy(['requestOrder' => $requestOrder], ['createdAt' => 'DESC']);
        return $this->render('request_order/show.html.twig', [
            'request_order' => $requestOrder,
            'form' => $form->createView(),
            'messages' => $messages
        ]);
    }



    #[Route('/{id}/edit', name: 'app_request_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RequestOrder $requestOrder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestOrderType::class, $requestOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_request_order_show', ['id' => $requestOrder->getId()]);
        }

        return $this->render('request_order/edit.html.twig', [
            'request_order' => $requestOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_request_order_delete', methods: ['POST'])]
    public function delete(Request $request, RequestOrder $requestOrder, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $requestOrder->getId(), $request->request->get('_token'))) {
            //Request Order messages
            $messages = $requestOrder->getMessages();

            // collection messages delete
            foreach ($messages as $message) {
                $entityManager->remove($message);
            }

            $entityManager->remove($requestOrder);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_request_order_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/pastry-chef/{id}/requests', name: 'app_view_pastry_chef_requests', methods: ['GET'])]
    public function viewPastryChefRequests(PastryChef $pastryChef): Response
    {
        //Request Order PastryChef
        $requests = $pastryChef->getRequestOrders()->getValues();
        //tri 
        usort($requests, function (RequestOrder $a, RequestOrder $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return $this->render('request_order/requests.html.twig', [
            'requests' => $requests,
        ]);
    }

    #[Route('/client/{id}/requests', name: 'app_view_client_requests', methods: ['GET'])]
    public function viewClientRequests(Client $client): Response
    {
        //Request Order Client
        $requests = $client->getRequestOrders()->getValues();
        usort($requests, function (RequestOrder $a, RequestOrder $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return $this->render('request_order/requests.html.twig', [
            'requests' => $requests,
        ]);
    }
}
