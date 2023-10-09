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
    
        $message->setChannel($channel); 
        $message->setUserId($user);
        $message->setText($data['message']);
    
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json([
            'message' => 'créé'
        ]);
    }


        
    #[Route('/messagesChannels/{id}', methods: ['GET'])]
    public function getMessageFromChannel
    (int $id, Request $request, MessageRepository $MessageRepository, ChannelRepository $channelRepository,
    UserRepository $UserRepository): Response
    {
            // Rechercher le canal en fonction de son ID
            $channel = $channelRepository->find($id);
        
            if (!$channel) {
                // Gérez le cas où le canal n'a pas été trouvé
                return new JsonResponse(['error' => 'Canal non trouvé'], 404);
            }
        
            // Récupérer tous les messages associés à ce canal
            $messages = $MessageRepository->findBy(['channel' => $channel]);


        
            // Créez un tableau pour stocker les données des messages
            $messageData = [];
        
            // Parcourez les messages et collectez les informations requises
            foreach ($messages as $message) {
                $user = $message->getUserId(); 
                $messageData[] = [
                    'id' => $message->getId(),
                    'message' => $message->getText(),
                    'user' => [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                    ],
                ];
            }
        
            // Retournez les messages sous forme de réponse JSON
            return $this->json(['messages' => $messageData]);

         
    }
}