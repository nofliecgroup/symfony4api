<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VillesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VillesRepository::class)]
class Villes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?int $id = null;

    #[ORM\Column(length: 50, type: 'string')]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?string $villenom = null;

    #[ORM\Column(length: 6, type: 'string')]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?string $codepostal = null;

    #[ORM\OneToMany(mappedBy: 'villedepart', targetEntity: Trajets::class)]
    
    private Collection $trajets;

    #[ORM\OneToMany(mappedBy: 'villearrive', targetEntity: Trajets::class)]
    private Collection $arrivetrajets;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->arrivetrajets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVillenom(): ?string
    {
        return $this->villenom;
    }

    public function setVillenom(string $villenom): self
    {
        $this->villenom = $villenom;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(string $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * @return Collection<int, Trajets>
     */
    public function getTrajets(): Collection
    {
        return $this->trajets;
    }

    public function addTrajet(Trajets $trajet): self
    {
        if (!$this->trajets->contains($trajet)) {
            $this->trajets->add($trajet);
            $trajet->setVilledepart($this);
        }

        return $this;
    }

    public function removeTrajet(Trajets $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            // set the owning side to null (unless already changed)
            if ($trajet->getVilledepart() === $this) {
                $trajet->setVilledepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trajets>
     */
    public function getArrivetrajets(): Collection
    {
        return $this->arrivetrajets;
    }

    public function addArrivetrajet(Trajets $arrivetrajet): self
    {
        if (!$this->arrivetrajets->contains($arrivetrajet)) {
            $this->arrivetrajets->add($arrivetrajet);
            $arrivetrajet->setVillearrive($this);
        }

        return $this;
    }

    public function removeArrivetrajet(Trajets $arrivetrajet): self
    {
        if ($this->arrivetrajets->removeElement($arrivetrajet)) {
            // set the owning side to null (unless already changed)
            if ($arrivetrajet->getVillearrive() === $this) {
                $arrivetrajet->setVillearrive(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->villenom;
    }
}
