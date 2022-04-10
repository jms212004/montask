<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email')
                ->setRequired(true)
                ->setLabel('Email'),
            TextField::new('password')
                ->setLabel("New Password")
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setRequired(false)
                ->setHelp('If the right is not given, leave the field blank.')
                ->hideOnIndex()
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'New password'],
                    'second_options' => ['label' => 'Repeat password'],
                ]),
        ];
    }

}
