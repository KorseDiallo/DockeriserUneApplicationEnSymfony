<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\StatutController;

#[ORM\Entity(repositoryClass: StatutRepository::class)]

#[ApiResource(operations: [
    
    new Post(
        name: 'ajouter_statut', 
        uriTemplate: '/api/ajouterStatut', 
        controller: StatutController::class.'::ajouterStatus'
    ),
    new Delete(
        name: 'supprimer_statut', 
        uriTemplate: '/api/supprimerStatut/{id}', 
        controller:StatutController::class.'::supprimerStatut'
    ),
    new Put(
        name: 'modifier_statut', 
        uriTemplate: '/api/modifierStatut/{id}', 
        controller:StatutController::class.'::modifierStatut'
    )
])]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'Statut', targetEntity: Candidature::class)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
            $candidature->setStatut($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getStatut() === $this) {
                $candidature->setStatut(null);
            }
        }

        return $this;
    }
}
