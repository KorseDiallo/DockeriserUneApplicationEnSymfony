<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class StatutController extends AbstractController
{
    #[Route('/api/ajouterStatut', name: 'ajouter_statut')]

    public function ajouterStatus(Request $request,SerializerInterface $serializer,EntityManagerInterface $em): JsonResponse
    {
        $status=$serializer->deserialize($request->getContent(),Statut::class,'json');
        $em->persist($status);
        $em->flush();
        return new JsonResponse(['message' => 'Statut enregistrer avec Succès'], Response::HTTP_CREATED);
    }
    

    #[Route('/api/supprimerStatut/{id}', name: 'supprimer_statut')]
    public function supprimerStatut(int $id,EntityManagerInterface $em,StatutRepository $statutRepository){
         $statut= $statutRepository->find($id);
         if($statut){
            $em->remove($statut);
            $em->flush();
            return new JsonResponse(['message' => 'Le Statut a été supprimée avec Succès'], Response::HTTP_OK);
         }else{
             return new JsonResponse(['message' => 'Statut non trouvée'], Response::HTTP_OK);
         }
    }

    #[Route('/api/modifierStatut/{id}', name: 'modifier_statut')]
   public function modifierStatut(int $id, Request $request,SerializerInterface $serializer,StatutRepository $statutRepository,EntityManagerInterface $em){
        $modifStatut= $statutRepository->find($id);
        if($modifStatut){
            $jsonmodifStatut= $serializer->deserialize($request->getContent(),Statut::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE =>$modifStatut]);

            $em->persist($jsonmodifStatut);
            $em->flush();

            return new JsonResponse(['message' => 'Le Statut a été modifiée avec Succès'], Response::HTTP_OK);
        }else{
            return new JsonResponse(['message' => 'Le Statut que vous voulez modifier n\'a pas été trouvé'], Response::HTTP_OK);
        }
   }
}
