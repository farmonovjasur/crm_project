<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UserCreateClientAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/users/{id}/clients', name: 'app_user_create_client', methods: ['POST'])]
    public function __invoke(User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $client = new Client();
        $client->setFullName($data['fullName'] ?? 'New Client');
        $client->setEmail($data['email'] ?? null);
        $client->setWorkplace($data['workplace'] ?? null);
        $client->setOwner($user);
        // Assuming company is optional or handled otherwise for now as CompanyFactory is also gone.
        // If Company is mandatory, we might need to fetch it. Pushing basic logic for now.
        
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->json($client, 201);
    }
}
