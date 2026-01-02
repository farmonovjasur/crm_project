<?php

namespace App\EventSubscriber;

use App\Entity\Company;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class CompanyOwnerSubscriber implements EventSubscriber
{
    public function __construct(private readonly Security $security)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Company) {
            return;
        }

        if ($entity->getOwner() !== null) {
            return;
        }

        $user = $this->security->getUser();
        if ($user) {
            $entity->setOwner($user);
        }
    }
}
