<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MarquesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: MarquesRepository::class)]
class Marques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes', 'reservations')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes', 'reservations')]
    private ?string $brandnom = null;

    #[ORM\OneToMany(mappedBy: 'idmarque', targetEntity: Voitures::class)]
    //#[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes', 'reservations')]
    #[MaxDepth(1)]
    private Collection $voitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandnom(): ?string
    {
        return $this->brandnom;
    }

    public function setBrandnom(string $brandnom): self
    {
        $this->brandnom = $brandnom;

        return $this;
    }

    /**
     * @return Collection<int, Voitures>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voitures $voiture): self
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setIdmarque($this);
        }

        return $this;
    }

    public function removeVoiture(Voitures $voiture): self
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getIdmarque() === $this) {
                $voiture->setIdmarque(null);
            }
        }

        return $this;
    }
}
