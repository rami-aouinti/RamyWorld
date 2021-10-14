<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
       return $crud
           ->setEntityLabelInSingular('User')
           ->setEntityLabelInPlural('Users')
           ->setSearchFields(['email'])
           ->setDefaultSort(['createdAt' => 'DESC'])
           ->showEntityActionsAsDropdown()
           ->setPaginatorPageSize(5);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @param Filters $filters
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('deparment'))
        ;
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email');
        yield AssociationField::new('deparment')
            ->setTemplatePath("admin/field/departments.html.twig")
            ->setCrudController(DepartmentCrudController::class)->hideOnIndex();
        yield AssociationField::new('profile')
            ->setTemplatePath("admin/field/profile.html.twig")
            ->setCrudController(ProfileCrudController::class)->hideOnIndex();
        yield AssociationField::new('education')->onlyOnDetail();
        yield AssociationField::new('skills')->onlyOnDetail();
        yield AssociationField::new('works')->onlyOnDetail();
        yield AssociationField::new('experiences')->onlyOnDetail();

        yield TextField::new('password')->hideOnIndex()->hideOnDetail();
    }
}
