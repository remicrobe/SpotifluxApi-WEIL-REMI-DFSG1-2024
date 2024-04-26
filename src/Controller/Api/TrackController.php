<?php

namespace App\Controller\Api;

use App\Entity\TrackEntity;
use App\Repository\AlbumEntityRepository;
use App\Repository\TrackEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Track")]
class TrackController extends AbstractController
{
    public function __construct(
        private TrackEntityRepository $trackEntityRepository,
        private AlbumEntityRepository $albumEntityRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) { }

    #[Route('/api/track', name: 'app_api_track_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $tracks = $this->trackEntityRepository->findAll();

        return $this->json($tracks, 200, [], ['groups' => 'track:read']);
    }

    #[Route('/api/track', name: 'app_api_track_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $track = new TrackEntity();
        $track->setTitle($data['title'] ?? null);
        $track->setDuration($data['duration'] ?? null);

        if($data['albumId'] !== null) {
            $album = $this->albumEntityRepository->find($data['albumId']);
            if ($album !== null) {
                $track->setAlbumEntity($album);
            }
        }

        $errors = $this->validator->validate($track);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        return $this->json($track, 201, [], ['groups' => 'track:read']);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_show', methods: ['GET'])]
    public function show(TrackEntity $track): JsonResponse
    {
        return $this->json($track, 200, [], ['groups' => 'track:read']);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_update', methods: ['PUT'])]
    public function update(Request $request, TrackEntity $track): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $track->setTitle($data['title'] ?? $track->getTitle());
        $track->setDuration($data['duration'] ?? $track->getDuration());

        if($data['albumId'] !== null) {
            $album = $this->albumEntityRepository->find($data['albumId']);
            if ($album !== null) {
                $track->setAlbumEntity($album);
            }
        }

        $errors = $this->validator->validate($track);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        return $this->json($track, 200, [], ['groups' => 'track:read']);
    }

    #[Route('/api/track/{id}', name: 'app_api_track_delete', methods: ['DELETE'])]
    public function delete(TrackEntity $track): JsonResponse
    {
        $this->entityManager->remove($track);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
