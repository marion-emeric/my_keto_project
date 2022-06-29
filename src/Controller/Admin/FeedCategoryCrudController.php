<?php

namespace App\Controller\Admin;

use App\Entity\FeedCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedCategory::class;
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
