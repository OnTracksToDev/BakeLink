<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Pastry;
use App\Form\PastryType;
use App\Entity\CommentPastry;
use App\Form\CommentPastryType;
use App\Security\Voter\PastryVoter;
use App\Repository\PastryRepository;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pastry')]
class PastryController extends AbstractController
{

    #[Route('/', name: 'app_pastry_index', methods: ['GET'])]
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
        Security $security
    ): Response {
        $user = $security->getUser();
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
            $pastry->setPastryChef($user);
            $entityManager->persist($pastry);
            $entityManager->flush();

            return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pastry/new.html.twig', [
            'pastry' => $pastry,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_pastry_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Pastry $pastry, EntityManagerInterface $entityManager): Response
    {
        $form = null;
        $user = $this->getUser();

        // Seuls les clients connectés peuvent commenter
        if ($this->isGranted('IS_AUTHENTICATED_FULLY') && $user instanceof Client) {
            $commentPastry = new CommentPastry();
            $commentPastry->setPastry($pastry);
            $commentPastry->setClient($user);

            $form = $this->createForm(CommentPastryType::class, $commentPastry);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($commentPastry);
                $entityManager->flush();
                $this->addFlash('success', 'Commentaire ajouté avec succès!');
                return $this->redirectToRoute('app_pastry_show', ['id' => $pastry->getId()]);
            }
        }

        // Récupérer les commentaires (pour tous)
        $comments = $entityManager->getRepository(CommentPastry::class)
            ->findBy(['pastry' => $pastry], ['createdAt' => 'DESC']);

        return $this->render('pastry/show.html.twig', [
            'comments' => $comments,
            'pastry' => $pastry,
            'form' => $form ? $form->createView() : null,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_pastry_edit', methods: ['GET', 'POST'])]
    #[IsGranted(PastryVoter::EDIT, subject: 'pastry')]
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

    #[Route('/{id}/delete', name: 'app_pastry_delete', methods: ['POST'])]
    #[IsGranted(PastryVoter::DELETE, subject: 'pastry')]
    public function delete(Request $request, Pastry $pastry, EntityManagerInterface $entityManager, ImageUploaderService $imageUploaderService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pastry->getId(), $request->request->get('_token'))) {

            //URL image avant de supprimer l'entité Pastry
            $imageUrl = $pastry->getPhotoUrl();
            // Vérifier si une URL d'image existe
            if ($imageUrl) {
                //service suppression image uniquement si l'URL existe
                $imageUploaderService->handleImageDelete($imageUrl);
            }
            //collection commentPastry
            $commentPastries = $pastry->getCommentPastries();
            foreach ($commentPastries as $commentPastry) {
                $entityManager->remove($commentPastry);
            }
            //récupère pastry chef associé a la patisserie
            $pastryChef = $pastry->getPastryChef();

            //supprime  de la BDD
            $entityManager->remove($pastry);
            $entityManager->flush();

            //redirection profil pastry chef
            if ($pastryChef) {

                return new RedirectResponse($this->generateUrl('app_pastry_chef_show', ['id' => $pastryChef->getId()]));
            }
        }
        return $this->redirectToRoute('app_pastry_index', [], Response::HTTP_SEE_OTHER);
    }
}
