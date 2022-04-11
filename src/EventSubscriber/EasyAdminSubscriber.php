<?php

namespace App\EventSubscriber;
use App\Entity\User;
use App\Entity\Tasks;

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
        //TODO : voir a specifier le type d'action réalisée
        
        $userConnect = $event->getAdminContext()->getUser();
        
       $this->logger->info("Action sur module admin par : ".$userConnect);
    }

    public function setEntityCreatedAt(BeforeEntityPersistedEvent $event)
    {
       $entity = $event->getEntityInstance();

       if (!$entity instanceof TimestampedInterface) {
           return;
       }

       $entity->setCreatedAt(new \DateTime('YYYY-MM-DD HH:MM:SS'));
    }

    public function setEntityUpdatedAt(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof TimestampedInterface) {
            return;
        }

        $entity->setUpdatedAt(new \DateTime('YYYY-MM-DD HH:MM:SS'));
    }
    

    public function encodePassword(BeforeEntityUpdatedEvent $event)
    {
        $user = $event->getEntityInstance();
        if (!$user instanceof User ) {
            return;
        }
        $this->setPassword($user);

    }

    public function setPassword(User $entity): void
      {
          $pass = $entity->getPassword();

          // encrypter le mot de passe
          $entity->setPassword(
            $this->passwordEncoder->hashPassword(
                    $entity,
                    $pass
                )
            );
          //ecrire dans la base de données
          $this->entityManager->persist($entity);
          $this->entityManager->flush();
      }
}