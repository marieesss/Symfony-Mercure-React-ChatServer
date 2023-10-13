<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérez l'utilisateur par son nom d'utilisateur ou autre champ d'identification
        $user = $userRepository->findOneBy(['username' => $data['username']]);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 401);
        }

        // Vérifiez le mot de passe
        if (!$encoder->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Mot de passe incorrect'], 401);
        }

        // Générez un jeton JWT
        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token,
        ]);
    }



    #[Route('/api/register', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $data = json_decode($request->getContent(), true);
        $user = new User();

        $user->setUsername($data['username']);
        $user->setMail($data['mail']);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();


        return $this->json([
            'users' => 'créé'
        ]);
    }
}