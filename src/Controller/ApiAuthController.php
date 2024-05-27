<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiAuthController extends AbstractController
{
    #[Route("/api/register", name:"api_register", methods:["POST"])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);

        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );

        $user->setNombre($data['nombre']);
        $user->setApellidos($data['apellidos']);
        $user->setUserName($data['userName']);
        $user->setTarjeta($data['tarjeta']);

        $user->setCvv(
            $passwordHasher->hashPassword($user, $data['cvv'])
        );

        $user->setFechaValidez(new \DateTime($data['fechaValidez']));
        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        // Generar el token JWT para el usuario recién registrado
        $token = $jwtManager->create($user);

        return new JsonResponse(['message' => 'User registered successfully', 'token' => $token], Response::HTTP_CREATED);
    }

    #[Route("/api/login", name:"api_login", methods:["POST"])]
    public function login(): JsonResponse
    {
        // El manejo del login es realizado automáticamente por lexik_jwt_authentication
        return new JsonResponse(['message' => 'Logged in successfully']);
    }
}
