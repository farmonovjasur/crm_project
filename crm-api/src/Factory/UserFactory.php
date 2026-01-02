<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function create(string $email, string $fullName, array $roles = []): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setRoles($roles);
        $user->setCreatedAt(new \DateTimeImmutable());

        return $user;
    }
}
