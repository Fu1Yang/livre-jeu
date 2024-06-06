<?php

namespace App\Entity;

use App\Repository\EtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapeRepository::class)]
class Etape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $texte_ambiance = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToOne(mappedBy: 'premiereEtape', cascade: ['persist', 'remove'])]
    private ?Aventure $aventureDeButee = null;

    #[ORM\ManyToOne(inversedBy: 'etapes')]
    private ?Aventure $aventure = null;

    #[ORM\ManyToOne(inversedBy: 'finsPossibles')]
    private ?Aventure $finAventure = null;

    /**
     * @var Collection<int, Partie>
     */
    #[ORM\OneToMany(targetEntity: Partie::class, mappedBy: 'etape')]
    private Collection $parties;

    /**
     * @var Collection<int, Alternative>
     */
    #[ORM\OneToMany(targetEntity: Alternative::class, mappedBy: 'etapePrecedente')]
    private Collection $alternatives;

    public function __construct()
    {
        $this->parties = new ArrayCollection();
        $this->alternatives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexteAmbiance(): ?string
    {
        return $this->texte_ambiance;
    }

    public function setTexteAmbiance(string $texte_ambiance): static
    {
        $this->texte_ambiance = $texte_ambiance;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAventureDeButee(): ?Aventure
    {
        return $this->aventureDeButee;
    }

    public function setAventureDeButee(?Aventure $aventureDeButee): static
    {
        // unset the owning side of the relation if necessary
        if ($aventureDeButee === null && $this->aventureDeButee !== null) {
            $this->aventureDeButee->setPremiereEtape(null);
        }

        // set the owning side of the relation if necessary
        if ($aventureDeButee !== null && $aventureDeButee->getPremiereEtape() !== $this) {
            $aventureDeButee->setPremiereEtape($this);
        }

        $this->aventureDeButee = $aventureDeButee;

        return $this;
    }

    public function getAventure(): ?Aventure
    {
        return $this->aventure;
    }

    public function setAventure(?Aventure $aventure): static
    {
        $this->aventure = $aventure;

        return $this;
    }

    public function getFinAventure(): ?Aventure
    {
        return $this->finAventure;
    }

    public function setFinAventure(?Aventure $finAventure): static
    {
        $this->finAventure = $finAventure;

        return $this;
    }

    /**
     * @return Collection<int, Partie>
     */
    public function getParties(): Collection
    {
        return $this->parties;
    }

    public function addParty(Partie $party): static
    {
        if (!$this->parties->contains($party)) {
            $this->parties->add($party);
            $party->setEtape($this);
        }

        return $this;
    }

    public function removeParty(Partie $party): static
    {
        if ($this->parties->removeElement($party)) {
            // set the owning side to null (unless already changed)
            if ($party->getEtape() === $this) {
                $party->setEtape(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    /**
     * @return Collection<int, Alternative>
     */
    public function getAlternatives(): Collection
    {
        return $this->alternatives;
    }

    public function addAlternative(Alternative $alternative): static
    {
        if (!$this->alternatives->contains($alternative)) {
            $this->alternatives->add($alternative);
            $alternative->setEtapePrecedente($this);
        }

        return $this;
    }

    public function removeAlternative(Alternative $alternative): static
    {
        if ($this->alternatives->removeElement($alternative)) {
            // set the owning side to null (unless already changed)
            if ($alternative->getEtapePrecedente() === $this) {
                $alternative->setEtapePrecedente(null);
            }
        }

        return $this;
    }
}


