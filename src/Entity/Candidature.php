<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\CandidatureController;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ApiResource(operations: [
    
    new Post(
        name: 'app_candidater', 
        uriTemplate: '/api/candidater/{formationId}/{statutId}', 
        controller: CandidatureController::class.'::candidater'
    ),
    new Post(
        name: 'app_refuser', 
        uriTemplate: '/api/refuser/{id}/{statutId}', 
        controller:CandidatureController::class.'::refuser'
    ),
    new Post(
        name: 'app_accepter', 
        uriTemplate: '/api/accepter/{id}/{statutId}', 
        controller:CandidatureController::class.'::accepter'
    )
])]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formations = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $Statut = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getFormations(): ?Formation
    {
        return $this->formations;
    }

    public function setFormations(?Formation $formations): static
    {
        $this->formations = $formations;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->Statut;
    }

    public function setStatut(?Statut $Statut): static
    {
        $this->Statut = $Statut;

        return $this;
    }

   
}
