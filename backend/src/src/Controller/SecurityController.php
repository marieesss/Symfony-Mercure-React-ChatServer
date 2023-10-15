<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{

    #[Route('/register', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $data = json_decode($request->getContent(), true);
        $user = new User();

        $user->setUsername($data['username']);
        $user->setEMail($data['mail']);
        $user->setRoles($data['ROLE_USER']);

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


    #[Route('/api/login_check', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérez l'utilisateur par son nom d'utilisateur ou autre champ d'identification
        $user = $userRepository->findOneBy(['username' => $data['username']]);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 401);
        }


        // $token = $jwtManager->create($user);

        return $this->json([
            'user' => 'user identifié',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
