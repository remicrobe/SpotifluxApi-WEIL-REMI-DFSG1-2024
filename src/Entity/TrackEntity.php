<?php

namespace App\Entity;

use App\Repository\TrackEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrackEntityRepository::class)]
class TrackEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['track:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['track:read'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['track:read'])]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    private ?AlbumEntity $albumEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAlbumEntity(): ?AlbumEntity
    {
        return $this->albumEntity;
    }

    public function setAlbumEntity(?AlbumEntity $albumEntity): static
    {
        $this->albumEntity = $albumEntity;

        return $this;
    }
}
