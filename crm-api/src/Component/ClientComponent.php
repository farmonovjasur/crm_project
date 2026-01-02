<?php

namespace App\Component;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\User;
use App\Factory\ClientFactory;
use App\Manager\ClientManager;

class ClientComponent
{
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly ClientManager $clientManager
    ) {
    }

    public function create(string $fullName, Company $company, User $owner, ?string $email = null, ?string $workplace = null): Client
    {
        $client = $this->clientFactory->create($fullName, $company, $owner, $email, $workplace);
        $this->clientManager->save($client);

        return $client;
    }

    public function update(Client $client, ?string $fullName = null, ?Company $company = null, ?User $owner = null, ?string $email = null, ?string $workplace = null): Client
    {
        if ($fullName !== null) {
            $client->setFullName($fullName);
        }

        if ($company !== null) {
            $client->setCompany($company);
        }

        if ($owner !== null) {
            $client->setOwner($owner);
        }

        if ($email !== null) {
            $client->setEmail($email);
        }

        if ($workplace !== null) {
            $client->setWorkplace($workplace);
        }

        $this->clientManager->save($client);

        return $client;
    }

    public function delete(Client $client): void
    {
        $this->clientManager->delete($client);
    }
}
