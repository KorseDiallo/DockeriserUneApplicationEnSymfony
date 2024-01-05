<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use App\Repository\FormationRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CandidatureController extends AbstractController
{
    #[Route('/api/candidater/{formationId}/{statutId}', name: 'app_candidater')]
    public function candidater(EntityManagerInterface $em,int $formationId,int $statutId,FormationRepository $formationRepository,StatutRepository $statutRepository ): JsonResponse {
        
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], Response::HTTP_UNAUTHORIZED);
        }

        $formation = $formationRepository->find($formationId);
        $statut = $statutRepository->find($statutId);

        if (!$formation || !$statut) {
            return new JsonResponse(['message' => 'Formation ou statut non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $candidature = new Candidature();
        
        $candidature->setUsers($user)
                    ->setFormations($formation)
                    ->setStatut($statut);

        $em->persist($candidature);
        $em->flush();

        return new JsonResponse(['message' => 'Candidature enregistrée avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/refuser/{id}/{statutId}', name: 'app_refuser')]
    public function refuser(EntityManagerInterface $em,int $id,int $statutId,CandidatureRepository $candidatureRepository,StatutRepository $statutRepository):JsonResponse{
        $candidature= $candidatureRepository->find($id);
        $statut= $statutRepository->find($statutId);
        $candidature->setStatut($statut);
        $em->persist($candidature);
        $em->flush();

        return new JsonResponse(['message' => 'Candidature Refusée Avec Succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/accepter/{id}/{statutId}', name: 'app_accepter')]
    public function accepter(EntityManagerInterface $em,int $id,int $statutId,CandidatureRepository $candidatureRepository,StatutRepository $statutRepository):JsonResponse{
        $candidature= $candidatureRepository->find($id);
        $statut= $statutRepository->find($statutId);
        $candidature->setStatut($statut);
        $em->persist($candidature);
        $em->flush();

        return new JsonResponse(['message' => 'Candidature Acceptée Avec Succès'], Response::HTTP_CREATED);
    }
}
