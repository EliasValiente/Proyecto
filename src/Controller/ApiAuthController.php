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
        // Decodificar los datos JSON de la solicitud
        $data = json_decode($request->getContent(), true);

        // Crear una nueva instancia de la entidad User
        $user = new User();
        $user->setEmail($data['email']);

        // Hashear la contraseña del usuario y establecerla en el objeto User
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );

        // Establecer otros campos del usuario
        $user->setNombre($data['nombre']);
        $user->setApellidos($data['apellidos']);
        $user->setUserName($data['userName']);
        $user->setTarjeta($data['tarjeta']);

        // Hashear el CVV y establecerlo en el objeto User
        $user->setCvv(
            $passwordHasher->hashPassword($user, $data['cvv'])
        );

        // Establecer la fecha de validez de la tarjeta de crédito
        $user->setFechaValidez(new \DateTime($data['fechaValidez']));
        
        // Asignar el rol de usuario al nuevo usuario
        $user->setRoles(['ROLE_USER']);

        // Persistir el nuevo usuario en la base de datos
        $entityManager->persist($user);
        $entityManager->flush();

        // Generar el token JWT para el usuario recién registrado
        $token = $jwtManager->create($user);

        // Devolver una respuesta JSON con un mensaje de éxito y el token JWT
        return new JsonResponse(['message' => 'User registered successfully', 'token' => $token], Response::HTTP_CREATED);
    }

    #[Route("/api/login", name:"api_login", methods:["POST"])]
    public function login(): JsonResponse
    {
        // El manejo del login es realizado automáticamente por lexik_jwt_authentication
        return new JsonResponse(['message' => 'Logged in successfully']);
    }
}
