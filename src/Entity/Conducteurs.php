<?php

namespace App\Entity;

use App\Repository\ConducteursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConducteursRepository::class)]
class Conducteurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Personnes::class, cascade: ['persist'], inversedBy: 'conducteurs')]
   // #[ORM\JoinColumn(name: 'idpersc', referencedColumnName: 'id', nullable: false)]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]
    private ?Personnes $idpersc = null;

    #[ORM\ManyToOne(targetEntity: Trajets::class, cascade: ['persist'], inversedBy: 'conducteurs')]
    #[Groups('conducteurs', 'reservations', 'personnes', 'trajets', 'voitures', 'marques', 'villes')]

    #[ORM\JoinColumn(nullable: false)]
    
    private ?Trajets $idtrajetc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdpersc(): ?Personnes
    {
        return $this->idpersc;
    }

    public function setIdpersc(?Personnes $idpersc): self
    {
        $this->idpersc = $idpersc;

        return $this;
    }

    public function getIdtrajetc(): ?Trajets
    {
        return $this->idtrajetc;
    }

    public function setIdtrajetc(?Trajets $idtrajetc): self
    {
        $this->idtrajetc = $idtrajetc;

        return $this;
    }
}
