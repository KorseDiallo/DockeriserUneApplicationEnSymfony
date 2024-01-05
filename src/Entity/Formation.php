<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\FormationController;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ApiResource(operations: [
    
    new Post(
        name: 'app_formation', 
        uriTemplate: '/api/CreerFormation', 
        controller: FormationController::class.'::creerFormation'
    ),
    new Delete(
        name: 'supprimer_formation', 
        uriTemplate: '/api/supprimerFormation/{id}', 
        controller:FormationController::class.'::supprimerFormation'
    ),
    new Put(
        name: 'modifier_formation', 
        uriTemplate: '/api/modifierFormation/{id}', 
        controller:FormationController::class.'::modifierFormation'
    )
])]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $durer = null;

    #[ORM\OneToMany(mappedBy: 'formations', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurer(): ?string
    {
        return $this->durer;
    }

    public function setDurer(string $durer): static
    {
        $this->durer = $durer;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setFormations($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getFormations() === $this) {
                $candidature->setFormations(null);
            }
        }

        return $this;
    }
}
