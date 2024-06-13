<?php

namespace App\Controller;

use App\Entity\Reproduccion;
use App\Entity\Pelicula;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ApiReproduccionController extends AbstractController
{
    #[Route('/api/reproductions', name: 'api_record_reproduction', methods: ['POST'])]
    public function recordReproduction(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Decodificar los datos JSON de la solicitud
        $data = json_decode($request->getContent(), true);

        // Obtener el usuario autenticado
        $user = $this->getUser();
        if (!$user) {
            // Devolver un error 401 si el usuario no está autenticado
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Buscar la película por ID en la base de datos
        $pelicula = $entityManager->getRepository(Pelicula::class)->find($data['movieId']);
        if (!$pelicula) {
            // Devolver un error 404 si la película no se encuentra
            return new JsonResponse(['message' => 'Movie not found'], Response::HTTP_NOT_FOUND);
        }

        // Crear una nueva instancia de Reproduccion y establecer sus propiedades
        $reproduccion = new Reproduccion();
        $reproduccion->setUser($user);
        $reproduccion->setPelicula($pelicula);
        $reproduccion->setFecha(new \DateTime($data['date']));

        // Persistir la nueva reproducción en la base de datos
        $entityManager->persist($reproduccion);
        $entityManager->flush();

        // Devolver una respuesta JSON con un mensaje de éxito
        return new JsonResponse(['message' => 'Reproduction recorded successfully'], Response::HTTP_CREATED);
    }
}
