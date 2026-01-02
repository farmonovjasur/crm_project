<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates an admin user (email/password) if it does not exist.'
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = 'admin@crm.local';
        $plainPassword = 'Admin12345';
        $fullName = 'CRM Admin';

        $repo = $this->em->getRepository(User::class);
        $existing = $repo->findOneBy(['email' => $email]);

        if ($existing) {
            $output->writeln(sprintf('Admin already exists: %s', $email));
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setRoles(['ROLE_ADMIN']);

        // make:user odatda plain password field bermaydi; biz hashed password set qilamiz
        $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);

        // User entity'da setPassword() yoki setHashedPassword() nomi farq qilishi mumkin.
        // Sizdagi method nomini moslab qo'ying:
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Admin created:');
        $output->writeln(sprintf('  email: %s', $email));
        $output->writeln(sprintf('  password: %s', $plainPassword));

        return Command::SUCCESS;
    }
}
