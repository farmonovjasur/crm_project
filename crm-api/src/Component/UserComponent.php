<?php

namespace App\Component;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Manager\UserManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserComponent
{
    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly UserManager $userManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function create(string $email, string $fullName, string $password, array $roles = []): User
    {
        $user = $this->userFactory->create($email, $fullName, $roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->userManager->save($user);

        return $user;
    }

    public function update(User $user, ?string $email = null, ?string $fullName = null, ?string $password = null, ?array $roles = []): User
    {
        if ($email !== null) {
            $user->setEmail($email);
        }

        if ($fullName !== null) {
            $user->setFullName($fullName);
        }

        if ($password !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        if ($roles !== null) {
            $user->setRoles($roles);
        }

        $this->userManager->save($user);

        return $user;
    }

    public function delete(User $user): void
    {
        $this->userManager->delete($user);
    }
}
