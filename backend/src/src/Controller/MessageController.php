<?php

namespace App\Controller;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\ChannelRepository; 
use App\Repository\UserRepository; 

class MessageController extends AbstractController
{
    #[Route('/message', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, MessageRepository $MessageRepository, ChannelRepository $channelRepository,
    UserRepository $UserRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $message = new Message();
    
        // Fetch the Channel entity based on the channelId
        $channel = $channelRepository->find($data['channelId']);

        $user = $UserRepository->find($data['userId']);
    
        if (!$channel) {
        }
    
        $message->setChannel($channel); // Set the Channel entity
        $message->setUserId($user);
        $message->setText($data['message']);
    
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json([
            'message' => 'créé'
        ]);
    
        
    }
}
