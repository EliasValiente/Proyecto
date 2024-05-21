<?php

namespace App\Controller\Admin;

use App\Entity\Pelicula;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PeliculaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pelicula::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titulo'),
            TextField::new('director'),
            TextField::new('categoria'),
            NumberField::new('duracion'),
            TextField::new('sinopsis'),
            TextField::new('imagen')
        ];
    }

}