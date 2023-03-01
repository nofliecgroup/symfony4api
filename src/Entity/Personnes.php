<?php /** @noinspection ALL */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonnesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonnesRepository::class)]
class Personnes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('personnes', 'reservations', 'conducteurs', 'trajets', 'voitures', 'marques', 'villes')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('personnes', 'reservations', 'conducteurs', 'trajets', 'voitures', 'marques', 'villes')]
    private ?string $prenom = null;

    #[ORM\Column(length: 30)]
    #[Groups('personnes', 'reservations', 'conducteurs', 'trajets', 'voitures', 'marques', 'villes')]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    #[Groups('personnes', 'reservations', 'conducteurs', 'trajets', 'voitures', 'marques', 'villes')]
    private ?string $telephone = null;

    #[ORM\ManyToOne(targetEntity: Villes::class, inversedBy: 'personnes', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\Column(length: 100)]
    #[Groups('personnes', 'reservations', 'conducteurs', 'trajets', 'voitures', 'marques', 'villes')]
    private  $ville;

    #[ORM\OneToMany(mappedBy: 'idpers', targetEntity: Reservations::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'idpersc', targetEntity: Conducteurs::class)]
    private Collection $conducteurs;

    #[ORM\OneToOne(mappedBy: 'idpers', cascade: ['persist', 'remove'], targetEntity: Users::class)]
    #[Groups('personnes', 'users')]
    private ?Users $users = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->conducteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        if ($prenom !== null) {
            $this->prenom = $prenom;
        }
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

  /*  public function setVille(Villes $ville): self
    {
       // $this->ville = $ville;
        //$this->ville = $ville->getVillenom() . ' ' . $ville->getCodepostal();
        $randomVille = $ville[array_rand($ville)];
        $this->ville = $randomVille;

        return $this;
    }*/

  /*  public function setVille($ville): self
    {
        if (is_array($ville)) {
            $randomVille = $ville[array_rand($ville)];
            $this->ville = $randomVille;
        } elseif ($ville instanceof Villes) {
            $this->ville = $ville;
        } else {
            throw new \InvalidArgumentException('$ville must be an instance of Villes or an array of Villes objects');
        }

        return $this;
    }*/

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

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
            $reservation->setIdpers($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdpers() === $this) {
                $reservation->setIdpers(null);
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
            $conducteur->setIdpersc($this);
        }

        return $this;
    }

    public function removeConducteur(Conducteurs $conducteur): self
    {
        if ($this->conducteurs->removeElement($conducteur)) {
            // set the owning side to null (unless already changed)
            if ($conducteur->getIdpersc() === $this) {
                $conducteur->setIdpersc(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setIdpers(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getIdpers() !== $this) {
            $users->setIdpers($this);
        }

        $this->users = $users;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }
}
