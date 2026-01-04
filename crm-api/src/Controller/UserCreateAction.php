<?php

namespace App\Controller;

use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UserCreateAction extends AbstractController
{
    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly UserManager $userManager
    ) {
    }

    public function __invoke(Request $request): User
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userFactory->create(
            $data['email'],
            $data['fullName'],
            $data['password'],
            $data['roles'] ?? []
        );

        $this->userManager->save($user);

        return $user;
    }
}
