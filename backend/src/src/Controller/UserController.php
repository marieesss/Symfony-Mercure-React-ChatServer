<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;


class UserController extends AbstractController
{
    #[Route('/users', methods: ['GET'])]
    public function index(userRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->getId(),
                'name' => $user->getUsername(),
                'email' => $user->getEMail(),
                // Ajoutez d'autres informations sur le canal si nécessaire
            ];
        }

        return $this->json(
            $userData
        );
    }
}
