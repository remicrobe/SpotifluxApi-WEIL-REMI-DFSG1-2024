<?php

namespace App\Controller\Api;

use App\Entity\ArtistEntity;
use App\Repository\ArtistEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Artist")]
class ArtistController extends AbstractController
{
    public function __construct(
        private ArtistEntityRepository $artistEntityRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) { }

    #[Route('/api/artist', name: 'app_api_artist_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $artists = $this->artistEntityRepository->findAll();

        return $this->json($artists, 200, [], ['groups' => 'artist:read']);
    }

    #[Route('/api/artist', name: 'app_api_artist_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $artist = new ArtistEntity();
        $artist->setName($data['name'] ?? null);

        $errors = $this->validator->validate($artist);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        return $this->json($artist, 201, [], ['groups' => 'artist:read']);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_show', methods: ['GET'])]
    public function show(ArtistEntity $artist): JsonResponse
    {
        return $this->json($artist, 200, [], ['groups' => 'artist:read']);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_update', methods: ['PUT'])]
    public function update(Request $request, ArtistEntity $artist): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $artist->setName($data['name'] ?? $artist->getName());

        $errors = $this->validator->validate($artist);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        return $this->json($artist, 200, [], ['groups' => 'artist:read']);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist_delete', methods: ['DELETE'])]
    public function delete(ArtistEntity $artist): JsonResponse
    {
        $this->entityManager->remove($artist);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}