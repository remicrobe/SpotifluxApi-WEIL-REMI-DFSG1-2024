<?php

namespace App\Controller\Web;

use App\Entity\AlbumEntity;
use App\Form\AlbumEntityType;
use App\Repository\AlbumEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/album')]
class AlbumController extends AbstractController
{
    #[Route('/', name: 'app_web_album_index', methods: ['GET'])]
    public function index(AlbumEntityRepository $albumEntityRepository): Response
    {
        $album_entities = $albumEntityRepository->createQueryBuilder('a')
            ->leftJoin('a.artist', 'artist')
            ->addSelect('artist')
            ->getQuery()
            ->getResult();

        return $this->render('web/album/index.html.twig', [
            'album_entities' => $album_entities,
        ]);
    }

    #[Route('/new', name: 'app_web_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $albumEntity = new AlbumEntity();
        $form = $this->createForm(AlbumEntityType::class, $albumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($albumEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/album/new.html.twig', [
            'album_entity' => $albumEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_album_show', methods: ['GET'])]
    public function show(AlbumEntity $albumEntity, AlbumEntityRepository $albumEntityRepository): Response
    {
        $album_entities = $albumEntityRepository->createQueryBuilder('a')
            ->leftJoin('a.artist', 'artist')
            ->leftJoin('a.tracks', 'tracks')
            ->addSelect('tracks')
            ->addSelect('artist')
            ->getQuery()
            ->getResult();

        return $this->render('web/album/show.html.twig', [
            'album_entity' => $albumEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_web_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AlbumEntity $albumEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlbumEntityType::class, $albumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web/album/edit.html.twig', [
            'album_entity' => $albumEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_album_delete', methods: ['POST'])]
    public function delete(Request $request, AlbumEntity $albumEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$albumEntity->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($albumEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
