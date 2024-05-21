<?php

namespace App\Controller\Admin;

use App\Entity\Suscripcion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SuscripcionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suscripcion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            NumberField::new('duracion'),
            NumberField::new('precio'),
            TextField::new('descripcion')


        ];
    }
    
}