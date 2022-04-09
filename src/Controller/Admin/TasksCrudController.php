<?php

namespace App\Controller\Admin;

use App\Entity\Tasks;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TasksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tasks::class;
    }
    

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
