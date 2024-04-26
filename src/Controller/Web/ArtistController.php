<?php

namespace App\Controller\Web;

use App\Entity\ArtistEntity;
use App\Form\ArtistEntityType;
use App\Repository\ArtistEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/artist')]
class ArtistController extends AbstractController
{
    #[Route('/', name: 'app_web_artist_index', methods: ['GET'])]
    public function index(ArtistEntityRepository $artistEntityRepository): Response
    {
        return $this->render('web/artist/index.html.twig', [
            'artist_entities' => $artistEntityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_web_artist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artistEntity = new ArtistEntity();
        $form = $this->createForm(ArtistEntityType::class, $artistEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artistEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/artist/new.html.twig', [
            'artist_entity' => $artistEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_artist_show', methods: ['GET'])]
    public function show(ArtistEntity $artistEntity): Response
    {
        return $this->render('web/artist/show.html.twig', [
            'artist_entity' => $artistEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_web_artist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArtistEntity $artistEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtistEntityType::class, $artistEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/artist/edit.html.twig', [
            'artist_entity' => $artistEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_artist_delete', methods: ['POST'])]
    public function delete(Request $request, ArtistEntity $artistEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artistEntity->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($artistEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_artist_index', [], Response::HTTP_SEE_OTHER);
    }
}
