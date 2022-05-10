<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrajetRepository::class)
 */
class Trajet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     */
    private $ville_dep;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     */
    private $ville_arr;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_trajet;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="trajets")
     */
    private $pers;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="trajet")
     */
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleDep(): ?Ville
    {
        return $this->ville_dep;
    }

    public function setVilleDep(?Ville $ville_dep): self
    {
        $this->ville_dep = $ville_dep;

        return $this;
    }

    public function getVilleArr(): ?Ville
    {
        return $this->ville_arr;
    }

    public function setVilleArr(?Ville $ville_arr): self
    {
        $this->ville_arr = $ville_arr;

        return $this;
    }

    public function getDateTrajet(): ?\DateTimeInterface
    {
        return $this->date_trajet;
    }

    public function setDateTrajet(\DateTimeInterface $date_trajet): self
    {
        $this->date_trajet = $date_trajet;

        return $this;
    }

    public function getPers(): ?Personne
    {
        return $this->pers;
    }

    public function setPers(?Personne $pers): self
    {
        $this->pers = $pers;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setTrajet($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getTrajet() === $this) {
                $inscription->setTrajet(null);
            }
        }

        return $this;
    }
}
