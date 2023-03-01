<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoituresRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoituresRepository::class)]
class Voitures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques')]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups('personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques')]
    private ?string $modele = null;

    #[ORM\Column]
    #[Groups('personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques')]
    private ?int $nbseats = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[Groups('personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques')]
    private ?Marques $idmarque = null;

    #[ORM\Column(length: 10)]
    #[Groups('personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques')]
    private ?string $immatriculation = null;

    #[ORM\OneToMany(mappedBy: 'idvoiture', targetEntity: Trajets::class)]
   // #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private Collection $trajets;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNbseats(): ?int
    {
        return $this->nbseats;
    }

    public function setNbseats(int $nbseats): self
    {
        $this->nbseats = $nbseats;

        return $this;
    }

    public function getIdmarque(): ?Marques
    {
        return $this->idmarque;
    }

    public function setIdmarque(?Marques $idmarque): self
    {
        $this->idmarque = $idmarque;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

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
            $trajet->setIdvoiture($this);
        }

        return $this;
    }

    public function removeTrajet(Trajets $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            // set the owning side to null (unless already changed)
            if ($trajet->getIdvoiture() === $this) {
                $trajet->setIdvoiture(null);
            }
        }

        return $this;
    }

    public function getNbseatsAvailable(): int
    {
        $nbseats = $this->getNbseats();
        $nbseatsAvailable = $nbseats;
        // foreach ($this->getTrajets() as $trajet) {
        //     $nbseatsAvailable -= $trajet->getNbseats();
        // }
        if ($nbseatsAvailable < 0) {
            $nbseatsAvailable = 0;
        }
        return $nbseatsAvailable;
    }


}
