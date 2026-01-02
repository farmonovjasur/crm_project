<?php

namespace App\Controller;

use App\Component\CompanyComponent;
use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/custom/companies')]
class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CompanyComponent $companyComponent
    ) {
    }

    #[Route('', name: 'app_company_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // In a real app, you'd get the current user from security
        $owner = $this->getUser(); 
        if (!$owner instanceof User) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $company = $this->companyComponent->create(
            $data['name'],
            $owner,
            $data['email'] ?? null,
            $data['password'] ?? null
        );

        return $this->json($company, 201);
    }

    #[Route('/{id}', name: 'app_company_update', methods: ['PATCH'])]
    public function update(Company $company, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->companyComponent->update(
            $company,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null
        );

        return $this->json($company);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['DELETE'])]
    public function delete(Company $company): JsonResponse
    {
        $this->companyComponent->delete($company);

        return $this->json(null, 204);
    }
}
