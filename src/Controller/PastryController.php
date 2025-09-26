<?php

namespace App\Controller;

use App\Entity\Pastry;
use App\Form\PastryType;
use App\Service\ImageUploaderService;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ImageUploaderService $imageUploaderService,
    ): Response {
        $pastry = new Pastry();
        $form = $this->createForm(PastryType::class, $pastry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //service upload image
            $newImage = $form->get('photoUrl')->getData();
            if ($newImage) {
                $imageUrl = $imageUploaderService->handleImageUpload($newImage);
                //URL image
                $pastry->setPhotoUrl($imageUrl);
            }
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
    public function edit(Request $request, Pastry $pastry, EntityManagerInterface $entityManager, ImageUploaderService $imageUploaderService): Response
    {
        $form = $this->createForm(PastryType::class, $pastry, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ancienne image pour suppression
            $oldImageUrl = $pastry->getPhotoUrl();
            // la nouvelle image
            $newImage = $form->get('photoUrl')->getData();
            //vérifie si une nouvelle image a été soumise
            if ($newImage) {
                // service upload nouvelle image
                $newImageUrl = $imageUploaderService->handleImageUpdate($oldImageUrl, $newImage);
                //enregistrement entité Pastry
                $pastry->setPhotoUrl($newImageUrl);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('pastry/edit.html.twig', [
            'pastry' => $pastry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pastry_delete', methods: ['POST'])]
    public function delete(Request $request, Pastry $pastry, EntityManagerInterface $entityManager, ImageUploaderService $imageUploaderService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pastry->getId(), $request->getPayload()->getString('_token'))) {
                        //URL image avant de supprimer l'entité Pastry
            $imageUrl = $pastry->getPhotoUrl();
            // Vérifier si une URL d'image existe
            if ($imageUrl) {
                //service suppression image uniquement si l'URL existe
                $imageUploaderService->handleImageDelete($imageUrl);
            }

            $entityManager->remove($pastry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
    }
}
