<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface; // Importez le ValidatorInterface
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;






class UserController extends AbstractController
{

    
    #[Route('/register', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager,
     ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher)
    {
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


       
    #[Route('/login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager,
     ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher,  UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        
        $user->setUsername($data['username']);

        
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );

        $user = $userRepository->findOneBy(['username' => $data['username']]);
        
        return $this->json([
            'users' => $user->getUsername()
        ]);
    }
}