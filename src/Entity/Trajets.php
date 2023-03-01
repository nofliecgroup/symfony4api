<?php

namespace App\Entity;

use App\Entity\Reservations;
use App\Entity\Conducteurs;
use App\Entity\Villes;
use App\Entity\Voitures;
use App\Entity\Marques;
use App\Entity\Personnes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrajetsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: TrajetsRepository::class)]
class Trajets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?int $nbkilometers = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private ?\DateTimeInterface $datetotravel = null;

    #[ORM\ManyToOne(inversedBy: 'trajets', targetEntity: Villes::class)]
    #[ORM\JoinColumn(name: 'ville_depart', referencedColumnName: 'id', nullable: true)]
    #[Groups('conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques', 'villes')]
    #[MaxDepth(1)]
    #[SerializedName("ville_depart")]
    private ?Villes $villedepart = null;

    #[ORM\ManyToOne(inversedBy: 'arrivetrajets')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques', 'villes')]
    #[MaxDepth(1)]
    #[SerializedName("ville_arrive")]
    private ?Villes $villearrive = null;

    #[ORM\ManyToOne(inversedBy: 'trajets')]
    #[Groups('conducteurs', 'reservations', 'villes', 'voitures', 'marques', 'villes', 'personnes')]
    private ?Voitures $idvoiture = null;

    #[ORM\OneToMany(mappedBy: 'idtrajet', targetEntity: Reservations::class)]
    //#[Groups('conducteurs', 'reservations', 'villes', 'reservations', 'voitures', 'marques', 'villes')]
    private Collection $reservations;
    #[ORM\OneToMany(mappedBy: 'idtrajetc', targetEntity: Conducteurs::class)]
    
    //#[ORM\OneToMany(mappedBy: 'idtrajetc', targetEntity: Conducteurs::class)]
    private Collection $conducteurs;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->conducteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbkilometers(): ?int
    {
        return $this->nbkilometers;
    }

    public function setNbkilometers(int $nbkilometers): self
    {
        $this->nbkilometers = $nbkilometers;

        return $this;
    }

    public function getDatetotravel(): ?\DateTimeInterface
    {
        return $this->datetotravel;
    }

    public function setDatetotravel(?\DateTimeInterface $datetotravel): self
    {
        $this->datetotravel = $datetotravel;

        return $this;
    }

    public function getVilledepart(): ?Villes
    {
        return $this->villedepart;
    }

    public function setVilledepart(?Villes $villedepart): self
    {
        $this->villedepart = $villedepart;

        return $this;
    }

    public function getVillearrive(): ?Villes
    {
        return $this->villearrive;
    }

    public function setVillearrive(?Villes $villearrive): self
    {
        $this->villearrive = $villearrive;

        return $this;
    }

    public function getIdvoiture(): ?Voitures
    {
        return $this->idvoiture;
    }

    public function setIdvoiture(?Voitures $idvoiture): self
    {
        $this->idvoiture = $idvoiture;

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdtrajet($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdtrajet() === $this) {
                $reservation->setIdtrajet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conducteurs>
     */
    public function getConducteurs(): Collection
    {
        return $this->conducteurs;
    }

    public function addConducteur(Conducteurs $conducteur): self
    {
        if (!$this->conducteurs->contains($conducteur)) {
            $this->conducteurs->add($conducteur);
            $conducteur->setIdtrajetc($this);
        }

        return $this;
    }

    public function removeConducteur(Conducteurs $conducteur): self
    {
        if ($this->conducteurs->removeElement($conducteur)) {
            // set the owning side to null (unless already changed)
            if ($conducteur->getIdtrajetc() === $this) {
                $conducteur->setIdtrajetc(null);
            }
        }

        return $this;
    }



    // public function getNbseats(): int
    // {
    //     return $this->getIdvoiture()->getNbplaces();
    // }

    
}
