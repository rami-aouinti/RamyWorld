<?php

namespace App\Controller\Admin;

use App\Entity\File;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return File::class;
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
