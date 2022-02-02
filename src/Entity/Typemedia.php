<?php

namespace App\Entity;

use App\Repository\TypemediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypemediaRepository::class)
 */
class Typemedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\OneToMany(targetEntity=Medias::class, mappedBy="type")
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity=GalerieVehicule::class, mappedBy="type", orphanRemoval=true)
     */
    private $galerieVehicules;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->galerieVehicules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return Collection|Medias[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Medias $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setType($this);
        }

        return $this;
    }

    public function removeMedia(Medias $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getType() === $this) {
                $media->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GalerieVehicule[]
     */
    public function getGalerieVehicules(): Collection
    {
        return $this->galerieVehicules;
    }

    public function addGalerieVehicule(GalerieVehicule $galerieVehicule): self
    {
        if (!$this->galerieVehicules->contains($galerieVehicule)) {
            $this->galerieVehicules[] = $galerieVehicule;
            $galerieVehicule->setType($this);
        }

        return $this;
    }

    public function removeGalerieVehicule(GalerieVehicule $galerieVehicule): self
    {
        if ($this->galerieVehicules->removeElement($galerieVehicule)) {
            // set the owning side to null (unless already changed)
            if ($galerieVehicule->getType() === $this) {
                $galerieVehicule->setType(null);
            }
        }

        return $this;
    }
}
