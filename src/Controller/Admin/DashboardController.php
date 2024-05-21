<?php

namespace App\Controller\Admin;

use App\Entity\Pelicula;
use App\Entity\Suscripcion;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Watchwise');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('User'),  
            MenuItem::linkToCrud('Users', 'fas fa-list', User::class),
            MenuItem::section('Peliculas'),
            MenuItem::linkToCrud('peliculas', 'fas fa-list', Pelicula::class),
            MenuItem::section('Suscripcion'),
            MenuItem::linkToCrud('Suscripciones', 'fa fa-list', Suscripcion::class)

        ];  
    }
}