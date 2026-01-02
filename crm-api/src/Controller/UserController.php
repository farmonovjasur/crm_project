<?php

namespace App\Controller;

use App\Component\UserComponent;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/custom/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserComponent $userComponent
    ) {
    }

    #[Route('', name: 'app_user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userComponent->create(
            $data['email'],
            $data['fullName'],
            $data['password'],
            $data['roles'] ?? []
        );

        return $this->json($user, 201);
    }

    #[Route('/{id}', name: 'app_user_update', methods: ['PATCH'])]
    public function update(User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->userComponent->update(
            $user,
            $data['email'] ?? null,
            $data['fullName'] ?? null,
            $data['password'] ?? null,
            $data['roles'] ?? null
        );

        return $this->json($user);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userComponent->delete($user);

        return $this->json(null, 204);
    }
}
