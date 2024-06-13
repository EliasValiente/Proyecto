<?php

namespace App\Controller;

use App\Entity\Suscripcion;
use App\Entity\User;
use App\Repository\SuscripcionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ApiSuscripcionController extends AbstractController
{
    private $security;
    private $entityManager;

    // Constructor para inicializar el servicio de seguridad y el EntityManager
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/subscribe', name: 'subscribe_user', methods: ['POST'])]
    public function subscribe(Request $request, SuscripcionRepository $suscripcionRepository): JsonResponse
    {
        // Obtener el usuario autenticado
        $user = $this->getUser();
        if (!$user instanceof User) {
            // Devolver un error 401 si el usuario no está autenticado
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        // Decodificar los datos JSON de la solicitud
        $data = json_decode($request->getContent(), true);
        $suscripcionId = $data['suscripcionId'] ?? null;

        if (!$suscripcionId) {
            // Devolver un error 400 si no se proporciona un ID de suscripción
            return new JsonResponse(['error' => 'No subscription ID provided'], 400);
        }

        // Buscar la suscripción por ID en el repositorio
        $suscripcion = $suscripcionRepository->find($suscripcionId);
        if (!$suscripcion) {
            // Devolver un error 404 si la suscripción no se encuentra
            return new JsonResponse(['error' => 'Subscription not found'], 404);
        }

        // Asignar la suscripción al usuario y persistir el cambio en la base de datos
        $user->setSuscripcion($suscripcion);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Devolver una respuesta JSON con un mensaje de éxito
        return new JsonResponse(['success' => 'Subscription updated'], 200);
    }

    #[Route('/api/suscripciones', name: 'app_api_suscripciones', methods: ['GET'])]
    public function getSubscriptions(SuscripcionRepository $suscripcionRepository): JsonResponse
    {
        // Obtener todas las suscripciones del repositorio
        $suscripciones = $suscripcionRepository->findAll();

        // Formatear los datos de las suscripciones para la respuesta JSON
        $data = [];
        foreach ($suscripciones as $suscripcion) {
            $data[] = [
                'id' => $suscripcion->getId(),
                'nombre' => $suscripcion->getNombre(),
                'duracion' => $suscripcion->getDuracion(),
                'precio' => $suscripcion->getPrecio(),
                'descripcion' => $suscripcion->getDescripcion(),
                'precio_mensual' => $suscripcion->getPrecioMensual(),
                'caracteristicas' => $suscripcion->getCaracteristicas(),
            ];
        }

        // Devolver una respuesta JSON con los datos de las suscripciones
        return new JsonResponse($data);
    }
}
