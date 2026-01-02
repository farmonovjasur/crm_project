<?php

namespace App\Manager;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(Company $company, bool $flush = true): void
    {
        $this->entityManager->persist($company);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function delete(Company $company, bool $flush = true): void
    {
        $this->entityManager->remove($company);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
