<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name:'app_api_registration')]
class ApiRegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', methods:['POST'])]
    public function Register(): Response
    {
        return $this->render('api_registration/index.html.twig', [
            'controller_name' => 'ApiRegistrationController',
        ]);
    }
}
