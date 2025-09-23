<?php

namespace App\Controller;

use App\Entity\RequestOrder;
use App\Form\RequestOrderType;
use App\Repository\RequestOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/request/order')]
final class RequestOrderController extends AbstractController
{
    #[Route(name: 'app_request_order_index', methods: ['GET'])]
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

    #[Route('/{id}', name: 'app_request_order_show', methods: ['GET'])]
    public function show(RequestOrder $requestOrder): Response
    {
        return $this->render('request_order/show.html.twig', [
            'request_order' => $requestOrder,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_request_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RequestOrder $requestOrder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestOrderType::class, $requestOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_request_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('request_order/edit.html.twig', [
            'request_order' => $requestOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_request_order_delete', methods: ['POST'])]
    public function delete(Request $request, RequestOrder $requestOrder, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requestOrder->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($requestOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_request_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
