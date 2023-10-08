<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; // You also need to import the Request class

class UserController extends AbstractController
{
    #[Route('/user', methods: ['POST'])]
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        return $this->json([
            'users' => 'créé'
        ]);
    }
}