<?php

namespace App\Controller;

use App\Entity\Channel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;

class ChannelController extends AbstractController
{
    #[Route('/channel', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
    
        // Vérifiez si le champ 'userIds' existe dans les données JSON
        if (isset($data['userIds'])) {
            $channel = new Channel();
            $channel->setName($data['name']);
            $userIds = $data['userIds'];
    
            // Récupérez les utilisateurs à partir des identifiants
            $users = $userRepository->findBy(['id' => $userIds]);
    
            // Ajoutez les utilisateurs au canal
            foreach ($users as $user) {
                $channel->addUsers($user);
            }
    
            // Enregistrez le canal en base de données
            $entityManager->persist($channel);
            $entityManager->flush();
    
            return $this->json([
                'channel' => $users
            ]);
        }
    
    }
    
}