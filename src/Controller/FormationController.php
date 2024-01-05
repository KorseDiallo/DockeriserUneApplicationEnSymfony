<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class FormationController extends AbstractController
{
    #[Route('/api/CreerFormation', name: 'app_formation')]
    public function creerFormation(Request $request,SerializerInterface $serializer,EntityManagerInterface $em): JsonResponse
    {
        $formation=$serializer->deserialize($request->getContent(),Formation::class,'json');
        $em->persist($formation);
        $em->flush();
        return new JsonResponse(['message' => 'Formation enregistrer avec Succès'], Response::HTTP_CREATED);
    }

   #[Route('/api/supprimerFormation/{id}', name: 'supprimer_formation')]
   public function supprimerFormation(int $id,EntityManagerInterface $em,FormationRepository $formationRepository){
        $formation= $formationRepository->find($id);
        if($formation){
           $em->remove($formation);
           $em->flush();
           return new JsonResponse(['message' => 'La Formation a été supprimée avec Succès'], Response::HTTP_OK);
        }else{
            return new JsonResponse(['message' => 'Formation non trouvée'], Response::HTTP_OK);
        }
   }

   #[Route('/api/modifierFormation/{id}', name: 'modifier_formation')]
   public function modifierFormation(int $id, Request $request,SerializerInterface $serializer,FormationRepository $formationRepository,EntityManagerInterface $em){
        $modifFormation= $formationRepository->find($id);
        if($modifFormation){
            $jsonModifFormation= $serializer->deserialize($request->getContent(),Formation::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE =>$modifFormation]);

            $em->persist($jsonModifFormation);
            $em->flush();

            return new JsonResponse(['message' => 'La Formation a été modifiée avec Succès'], Response::HTTP_OK);
        }else{
            return new JsonResponse(['message' => 'La Formation que vous voulez modifier n\'a pas été trouvé'], Response::HTTP_OK);
        }
   }
}
