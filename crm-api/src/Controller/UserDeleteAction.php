<?php

namespace App\Controller;

use App\Component\User\UserManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UserDeleteAction extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager
    ) {
    }

    #[Route('/api/custom/users/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function __invoke(User $user): JsonResponse
    {
        $this->userManager->delete($user);

        return $this->json(null, 204);
    }
}
