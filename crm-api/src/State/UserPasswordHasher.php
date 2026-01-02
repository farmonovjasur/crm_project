<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private readonly PersistProcessor $persistProcessor,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof User && $data->getPassword()) {
            // Only hash if password is not already hashed (check if it looks like a hash)
            $password = $data->getPassword();
            if (!preg_match('/^\$2[ayb]\$.{56}$/', $password) && strlen($password) < 60) {
                $hashedPassword = $this->passwordHasher->hashPassword($data, $password);
                $data->setPassword($hashedPassword);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}

