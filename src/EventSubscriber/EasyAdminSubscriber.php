<?php

namespace App\EventSubscriber;

use App\Model\TimestampedInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            //fonction a executer avant la réalisation
            BeforeEntityPersistedEvent::class => ['setEntityCreatedAt'],
            BeforeEntityUpdatedEvent::class => ['setEntityUpdatedAt'],

            BeforeCrudActionEvent::class => ['setEntityLogger'],
        ];
    }

    public function setEntityLogger(BeforeCrudActionEvent $event)
    {
        //$crud = $event->getAdminContext()->getCrud();
        $userConnect = $event->getAdminContext()->getUser();
        
       $this->logger->info("Action sur module admin par : ".$userConnect);
    }

    public function setEntityCreatedAt(BeforeEntityPersistedEvent $event)
    {
       $entity = $event->getEntityInstance();

       if (!$entity instanceof TimestampedInterface) {
           return;
       }

       $entity->setCreatedAt(new \DateTime());
    }

    public function setEntityUpdatedAt(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof TimestampedInterface) {
            return;
        }

        $entity->setUpdatedAt(new \DateTime());
    }
}