<?php

namespace App\Component;

use App\Entity\Company;
use App\Entity\User;
use App\Factory\CompanyFactory;
use App\Manager\CompanyManager;

class CompanyComponent
{
    public function __construct(
        private readonly CompanyFactory $companyFactory,
        private readonly CompanyManager $companyManager
    ) {
    }

    public function create(string $name, User $owner, ?string $email = null, ?string $password = null): Company
    {
        $company = $this->companyFactory->create($name, $owner, $email);
        
        if ($password !== null) {
            $company->setPassword($password);
        }

        $this->companyManager->save($company);

        return $company;
    }

    public function update(Company $company, ?string $name = null, ?string $email = null, ?string $password = null, ?User $owner = null): Company
    {
        if ($name !== null) {
            $company->setName($name);
        }

        if ($email !== null) {
            $company->setEmail($email);
        }

        if ($password !== null) {
            $company->setPassword($password);
        }

        if ($owner !== null) {
            $company->setOwner($owner);
        }

        $this->companyManager->save($company);

        return $company;
    }

    public function delete(Company $company): void
    {
        $this->companyManager->delete($company);
    }
}
