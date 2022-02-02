<?php

namespace App\Entity;

use App\Repository\MarchandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarchandRepository::class)
 */
class Marchand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Concessionnairemarchand::class, inversedBy="marchand", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Concessionnairemarchand;

    /**
     * @ORM\OneToMany(targetEntity=Vehicule::class, mappedBy="marchand")
     */
    private $vehicules;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConcessionnairemarchand(): ?Concessionnairemarchand
    {
        return $this->Concessionnairemarchand;
    }

    public function setConcessionnairemarchand(Concessionnairemarchand $Concessionnairemarchand): self
    {
        $this->Concessionnairemarchand = $Concessionnairemarchand;

        return $this;
    }

  
}
