<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return new JsonResponse(['error' => $error->getMessageKey()], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['result' => 'success']);
    }

    #[Route('/api/logout', name:'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        // The security layer will intercept this request
    }
}