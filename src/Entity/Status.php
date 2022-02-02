<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $nom;

 

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?bool
    {
        return $this->nom;
    }

    public function setNom(?bool $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
