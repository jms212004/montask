<?php

namespace App\EventSubscriber;
use App\Entity\User;

use App\Model\TimestampedInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordEncoder
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            //fonction a executer avant la réalisation
            BeforeEntityPersistedEvent::class => ['setEntityCreatedAt'],
            BeforeEntityUpdatedEvent::class => ['setEntityUpdatedAt'],

            BeforeCrudActionEvent::class => ['setEntityLogger'],

            BeforeEntityUpdatedEvent::class => 'encodePassword',

            
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
    

    public function encodePassword(BeforeEntityUpdatedEvent $event)
    {
        $user = $event->getEntityInstance();
        /*if ($user instanceof User ) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }*/
        $this->setPassword($user);
    }

    public function setPassword(User $entity): void
      {
          $pass = $entity->getPassword();

          //$entity->setPassword($this->userPasswordHasher->hashPassword($pass));
          $entity->setPassword(
            $this->passwordEncoder->hashPassword(
                    $entity,
                    $pass
                )
            );
          /*$entity->setPassword(
              $this->passwordEncoder->encodePassword(
                  $entity,
                  $pass
              )
          );*/
          $this->entityManager->persist($entity);
          $this->entityManager->flush();
      }
}