<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return new JsonResponse(['error' => $error->getMessageKey()], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUser();
        return new JsonResponse(['result' => $user ? $user->getUserIdentifier() : null]);
    }

    #[Route('/api/logout', name:'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        $ses = $this->$_SESSION;
        $ses->unset();
        $ses->delete();
    }

    #[Route('/api/logout-success', name: 'app_logout_success', methods: ['GET'])]
    public function logoutSuccess(): JsonResponse
    {
        return new JsonResponse(['message' => 'Logged out successfully']);
    }
}
