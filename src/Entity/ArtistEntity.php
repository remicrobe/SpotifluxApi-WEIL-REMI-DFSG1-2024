<?php

namespace App\Entity;

use App\Repository\ArtistEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ArtistEntityRepository::class)]
class ArtistEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['artist:read','album:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['artist:read','album:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, AlbumEntity>
     */
    #[ORM\OneToMany(targetEntity: AlbumEntity::class, mappedBy: 'artist')]
    private Collection $albumEntities;

    public function __construct()
    {
        $this->albumEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, AlbumEntity>
     */
    public function getAlbumEntities(): Collection
    {
        return $this->albumEntities;
    }

    public function addAlbumEntity(AlbumEntity $albumEntity): static
    {
        if (!$this->albumEntities->contains($albumEntity)) {
            $this->albumEntities->add($albumEntity);
            $albumEntity->setArtist($this);
        }

        return $this;
    }

    public function removeAlbumEntity(AlbumEntity $albumEntity): static
    {
        if ($this->albumEntities->removeElement($albumEntity)) {
            // set the owning side to null (unless already changed)
            if ($albumEntity->getArtist() === $this) {
                $albumEntity->setArtist(null);
            }
        }

        return $this;
    }
}
