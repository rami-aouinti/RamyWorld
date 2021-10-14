<?php

namespace App\Controller\Admin;

use App\Entity\Resume;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ResumeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resume::class;
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
