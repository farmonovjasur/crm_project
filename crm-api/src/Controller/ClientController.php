<?php

namespace App\Controller;

use App\Component\ClientComponent;
use App\Entity\Client;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/custom/clients')]
class ClientController extends AbstractController
{
    public function __construct(
        private readonly ClientComponent $clientComponent,
        private readonly \Doctrine\Persistence\ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'app_client_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $owner = $this->getUser();
        if (!$owner instanceof User) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        // Assuming company ID is passed
        $companyId = $data['companyId'] ?? null;
        $company = $this->doctrine->getRepository(Company::class)->find($companyId);

        if (!$company) {
            return $this->json(['error' => 'Company not found'], 404);
        }

        $client = $this->clientComponent->create(
            $data['fullName'],
            $company,
            $owner,
            $data['email'] ?? null,
            $data['workplace'] ?? null
        );

        return $this->json($client, 201);
    }

    #[Route('/{id}', name: 'app_client_update', methods: ['PATCH'])]
    public function update(Client $client, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->clientComponent->update(
            $client,
            $data['fullName'] ?? null,
            null, // company update not handled here for simplicity
            $data['email'] ?? null,
            $data['workplace'] ?? null
        );

        return $this->json($client);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['DELETE'])]
    public function delete(Client $client): JsonResponse
    {
        $this->clientComponent->delete($client);

        return $this->json(null, 204);
    }
}
