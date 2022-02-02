<?php
namespace App\Entity;
use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=PartenaireRepository::class)
 */
class Partenaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, inversedBy="partenaire", cascade={"persist", "remove"})
     */
    private $utilisateur;
    /**
     * @ORM\ManyToMany(targetEntity=Agent::class, inversedBy="partenaire")
     */
    private $agents;
   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\OneToOne(targetEntity=Medias::class, cascade={"persist", "remove"})
     */
    private $media;
    
    


 
    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->vehicules = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
    /**
     * @return Collection|Agent[]
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }
    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents[] = $agent;
        }
        return $this;
    }
    public function removeAgent(Agent $agent): self
    {
        $this->agents->removeElement($agent);
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function getActif(): ?bool
    {
        return $this->actif;
    }
    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }
    
    

    public function getMedia(): ?Medias
    {
        return $this->media;
    }

    public function setMedia(?Medias $media): self
    {
        $this->media = $media;

        return $this;
    }

   
}
