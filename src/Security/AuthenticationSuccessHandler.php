<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    // Constructor que inyecta el servicio de gestión de tokens JWT
    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    // Método que se ejecuta al tener éxito en la autenticación
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        /** @var UserInterface $user */
        $user = $token->getUser();  // Obtener el usuario autenticado
        $roles = $token->getRoleNames();  // Obtener los roles del usuario

        // Generar el token JWT para el usuario autenticado
        $jwt = $this->jwtManager->create($user);

        // Devolver una respuesta JSON con los roles del usuario y el token JWT
        return new JsonResponse([
            'roles' => $roles,
            'token' => $jwt,
        ]);
    }
}
