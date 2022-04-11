<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email')
                ->setRequired(true)
                ->setLabel('Email')
                ->setHelp('The email must be unique')
                ->formatValue(
                    function ($value) {
                        return is_null($value) ? '' : $value;
                    }
                );
            yield TextField::new('password')
                ->setLabel("Password")
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setRequired(false)
                ->setHelp('If the right is not given, leave the field blank.')
                ->hideOnIndex()
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'New password'],
                    'second_options' => ['label' => 'Repeat password'],
                ]);
            yield ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setHelp('Select roles for this user.')
                ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_AUTHOR' => 'warning'
                ])
                ->setChoices([
                    'Administrator' => 'ROLE_ADMIN',
                    'Author' => 'ROLE_AUTHOR'
                ]);

            yield BooleanField::new('isVerified')
                ->setLabel('Is Verified')
                ->setHelp('If the right is not given, leave the field blank.');

        

        /*if (Crud::PAGE_INDEX === $pageName) {
            return [$name, $cellphone, $email, $createdAt];
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $cellphone, $email, $createdAt, $pincheck, $stamps];
        }
        if (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            $name->setLabel('Nombre y apellido');
            $cellphone->setLabel('Teléfono (solo números)');
            $email->setLabel('Email (opcional)');
        }
        if (Crud::PAGE_NEW === $pageName) {
            return [$name, $cellphone, $email];
        }
        if (Crud::PAGE_EDIT === $pageName) {
            return [$name, $cellphone, $email];
        }*/
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['roles','email'])
            ->setDefaultSort(['email' => 'ASC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //->disable('delete')
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_ADMIN')
            ->setPermission(Action::SAVE_AND_CONTINUE, 'ROLE_ADMIN')
        ;
    }

    /*public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('email'));
    }*/

}
