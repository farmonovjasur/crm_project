<?php

namespace App\Manager;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(Client $client, bool $flush = true): void
    {
        $this->entityManager->persist($client);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function delete(Client $client, bool $flush = true): void
    {
        $this->entityManager->remove($client);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
