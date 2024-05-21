<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms(),
            TextField::new('nombre'),
            TextField::new('apellidos'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms(),
            ChoiceField::new('roles')
                ->setLabel('roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'Administrador' => 'ROLE_ADMIN',
                    'Manager' => 'ROLE_MANAGER',
                    'User' =>  'ROLE_USER'
            ])
        ];
    }

    private function encodePassword(User $user): void
    {
        $plainPassword = $user->getPassword();

        // Verificar si el campo de la contraseña está definido y no es nulo
        if ($plainPassword !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
    }

    
    
} 