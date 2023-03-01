<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationsRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Personnes::class, cascade: ['persist'], inversedBy: 'reservations')]
    //#[ORM\JoinColumn(name: 'idpers', referencedColumnName: 'id', nullable: false)]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private ?Personnes $idpers = null;

    #[ORM\ManyToOne( targetEntity: Trajets::class, cascade: ['persist'], inversedBy: 'reservations')]
    //#[ORM\JoinColumn(name: 'idtrajet', referencedColumnName: 'id', nullable: false)]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private ?Trajets $idtrajet = null;

    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private ?int $nseatsreserved = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdpers(): ?Personnes
    {
        return $this->idpers;
    }

    public function setIdpers(?Personnes $idpers): self
    {
        $this->idpers = $idpers;

        return $this;
    }

    public function getIdtrajet(): ?Trajets
    {
        return $this->idtrajet;
    }

    public function setIdtrajet(?Trajets $idtrajet): self
    {
        $this->idtrajet = $idtrajet;

        return $this;
    }

    public function getNseatsreserved(): ?int
    {

        return $this->nseatsreserved;
    }

    public function setNseatsreserved(int $nseatsreserved): self
    {
        $this->nseatsreserved = $nseatsreserved;

        return $this;
    }
}
