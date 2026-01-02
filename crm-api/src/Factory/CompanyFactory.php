<?php

namespace App\Factory;

use App\Entity\Company;
use App\Entity\User;

class CompanyFactory
{
    public function create(string $name, User $owner, ?string $email = null): Company
    {
        $company = new Company();
        $company->setName($name);
        $company->setOwner($owner);
        $company->setEmail($email);
        $company->setCreatedAt(new \DateTimeImmutable());

        return $company;
    }
}
