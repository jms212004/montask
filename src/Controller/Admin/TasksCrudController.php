<?php

namespace App\Controller\Admin;

use App\Entity\Tasks;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class TasksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tasks::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        
           
            yield TextField::new('task');

            //yield TextField::new('status');
            yield ChoiceField::new('status')->setChoices([
                'In progress' => 'en cours',
                'Close' => 'fini',
                'Canceled' => 'annulé',
                'Pending' => 'en attente',
            ]);

            if (Crud::PAGE_INDEX === $pageName) {
                yield DateTimeField::new('createdAt');
                yield DateTimeField::new('updatedAt');
            }
            

            

        
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['task','status'])
            ->setDefaultSort(['task' => 'ASC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //->disable('delete')
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::DETAIL, 'ROLE_USER')
            ->setPermission(Action::INDEX, 'ROLE_USER')
            ->setPermission(Action::EDIT, 'ROLE_USER')
            ->setPermission(Action::SAVE_AND_RETURN, 'ROLE_USER')
            ->setPermission(Action::SAVE_AND_CONTINUE, 'ROLE_USER')
        ;
    }
}
