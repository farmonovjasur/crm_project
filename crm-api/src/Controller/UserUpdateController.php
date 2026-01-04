<?php

namespace App\Controller;

use App\Component\User\UserManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class UserUpdateController extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(User $data, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        if (isset($payload['email'])) {
            $data->setEmail($payload['email']);
        }
        if (isset($payload['fullName'])) {
            $data->setFullName($payload['fullName']);
        }
        if (isset($payload['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $payload['password']);
            $data->setPassword($hashedPassword);
        }
        if (isset($payload['roles'])) {
            $data->setRoles($payload['roles']);
        }

        $this->userManager->save($data);

        return $this->json($data, 200, [], ['groups' => ['user:read']]);
    }
}
