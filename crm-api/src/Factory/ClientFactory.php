<?php

namespace App\Factory;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\User;

class ClientFactory
{
    public function create(string $fullName, Company $company, User $owner, ?string $email = null, ?string $workplace = null): Client
    {
        $client = new Client();
        $client->setFullName($fullName);
        $client->setCompany($company);
        $client->setOwner($owner);
        $client->setEmail($email);
        $client->setWorkplace($workplace);
        $client->setCreatedAt(new \DateTimeImmutable());

        return $client;
    }
}
