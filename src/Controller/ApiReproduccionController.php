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
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $pelicula = $entityManager->getRepository(Pelicula::class)->find($data['movieId']);
        if (!$pelicula) {
            return new JsonResponse(['message' => 'Movie not found'], Response::HTTP_NOT_FOUND);
        }

        $reproduccion = new Reproduccion();
        $reproduccion->setUser($user);
        $reproduccion->setPelicula($pelicula);
        $reproduccion->setFecha(new \DateTime($data['date']));

        $entityManager->persist($reproduccion);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Reproduction recorded successfully'], Response::HTTP_CREATED);
    }
}
