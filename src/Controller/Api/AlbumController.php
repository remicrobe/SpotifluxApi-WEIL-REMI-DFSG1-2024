<?php

namespace App\Controller\Api;

use App\Entity\AlbumEntity;
use App\Repository\AlbumEntityRepository;
use App\Repository\ArtistEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Album")]
class AlbumController extends AbstractController
{
    public function __construct(
        private AlbumEntityRepository $albumEntityRepository,
        private ArtistEntityRepository $artistEntityRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) { }

    #[Route('/api/album', name: 'app_api_album_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AlbumEntity::class, groups: ['album:read']))
        )
    )]
    public function list(): JsonResponse
    {
        $albums = $this->albumEntityRepository->findAll();

        return $this->json($albums, 200, [], ['groups' => 'album:read']);
    }

    #[Route('/api/album', name: 'app_api_album_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $album = new AlbumEntity();
        $album->setName($data['name'] ?? null);
        $album->setYear($data['year'] ?? null);

        if($data['artistId'] !== null) {
            $artist = $this->artistEntityRepository->find($data['artistId']);
            if ($artist !== null) {
                $album->setArtist($artist);
            }
        }

        $errors = $this->validator->validate($album);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->persist($album);
        $this->entityManager->flush();

        return $this->json($album, 201, [], ['groups' => 'album:read']);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_show', methods: ['GET'])]
    public function show(AlbumEntity $album): JsonResponse
    {
        return $this->json($album, 200, [], ['groups' => 'album:read']);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_update', methods: ['PUT'])]
    public function update(Request $request, AlbumEntity $album): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $album->setName($data['name'] ?? $album->getName());
        $album->setYear($data['year'] ?? $album->getYear());

        if($data['artistId'] !== null) {
            $artist = $this->artistEntityRepository->find($data['artistId']);
            if ($artist !== null) {
                $album->setArtist($artist);
            }
        }

        $errors = $this->validator->validate($album);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        return $this->json($album, 200, [], ['groups' => 'album:read']);
    }

    #[Route('/api/album/{id}', name: 'app_api_album_delete', methods: ['DELETE'])]
    public function delete(AlbumEntity $album): JsonResponse
    {
        $this->entityManager->remove($album);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
