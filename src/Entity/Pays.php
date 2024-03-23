<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'pays', targetEntity: PaysImage::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $paysImages;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'pays', targetEntity: Ville::class)]
    private Collection $villes;

    public function __construct()
    {
        $this->paysImages = new ArrayCollection();
        $this->villes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, PaysImage>
     */
    public function getPaysImages(): Collection
    {
        return $this->paysImages;
    }

    public function addPaysImage(PaysImage $paysImage): static
    {
        if (!$this->paysImages->contains($paysImage)) {
            $this->paysImages->add($paysImage);
            $paysImage->setPays($this);
        }

        return $this;
    }

    public function removePaysImage(PaysImage $paysImage): static
    {
        if ($this->paysImages->removeElement($paysImage)) {
            // set the owning side to null (unless already changed)
            if ($paysImage->getPays() === $this) {
                $paysImage->setPays(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Ville>
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): static
    {
        if (!$this->villes->contains($ville)) {
            $this->villes->add($ville);
            $ville->setPays($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): static
    {
        if ($this->villes->removeElement($ville)) {
            // set the owning side to null (unless already changed)
            if ($ville->getPays() === $this) {
                $ville->setPays(null);
            }
        }

        return $this;
    }
}
