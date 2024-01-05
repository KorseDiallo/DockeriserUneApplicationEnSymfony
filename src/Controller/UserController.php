<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/register', name: 'login_check')]
    public function register(Request $request,SerializerInterface $serializer,EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher ): JsonResponse
    {
        $user= $serializer->deserialize($request->getContent(), User::class,'json');
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        
        $em->persist($user);
        $em->flush();

        // $jsonUser= $serializer->serialize($user,'json',[]);

        return new JsonResponse(['message' => 'Enregistrement Effectuer avec Succès'], Response::HTTP_CREATED);
        
    }

   #[Route('/api/listCandidat', name: 'liste_candidat')]
   public function listeCandidature(UserRepository $userRepository,SerializerInterface $serializer){
        $listeCandidat= $userRepository->findBy(['roles'=>'Candidat']);
      
        $convert = $serializer->serialize($listeCandidat, 'json');
        // return $this->json($convert,200);
        // dd($listeCandidat);
        return $this->json([
            'status' => true,
            'message' => 'Liste des candidats',
            'data' => $convert,
        ]);
   }
}
