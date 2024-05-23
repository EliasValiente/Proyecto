<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    #[Route("/api/login", name:"app_login", methods:["POST"])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Este método puede estar vacío, será manejado por el firewall json_login
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route("/api/logout", name:"app_logout", methods:["POST"])]
    public function logout(): void
    {
        // Este método puede estar vacío, será manejado por el mecanismo de cierre de sesión de Symfony
    }

    #[Route("/api/logout/red", name:"app_logout_redir", methods:["GET"])]
    public function logoutRedir(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route("/vuelta", name:"app_admin_redir")]
    public function logoutAdminRedir(): Response
    {
        // Obtener la sesión actual
        $session = $this->requestStack->getSession();

        // Invalidar la sesión actual
        $session->invalidate();

        // Redirigir al frontend Angular
        return $this->redirect('http://localhost:4200/');
    }
}
