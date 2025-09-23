<?php

namespace App\EventListener;

use App\Entity\User;
use App\Entity\Pastry;
use App\Entity\Report;
use DateTimeImmutable;
use App\Entity\Message;
use Doctrine\ORM\Events;
use App\Entity\RequestOrder;
use App\Entity\CommentPastry;
use App\Entity\CommentPastryChef;
use App\Entity\Contact;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;


#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]

final class DateTimeListener
{
    #[AsEventListener(event: PrePersistEventArgs::class)]
    public function prePersist(PrePersistEventArgs $event): void
    {

        $entity = $event->getObject();
        if ($entity instanceof User) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof Pastry) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof Report) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof RequestOrder) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof Message) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof CommentPastryChef) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof CommentPastry) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof Contact) {
            $entity->setCreatedAt(new \DateTimeImmutable);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {

        $entity = $event->getObject();

        if ($entity instanceof Pastry) {
            $entity->setUpdatedAt(new \DateTimeImmutable);
        }
        if ($entity instanceof RequestOrder) {
            $entity->setUpdatedAt(new \DateTimeImmutable);
        }

        if ($entity instanceof CommentPastryChef) {
            $entity->setUpdatedAt(new DateTimeImmutable);
        }
        if ($entity instanceof CommentPastry) {
            $entity->setUpdatedAt(new DateTimeImmutable);
        }
    }
}
