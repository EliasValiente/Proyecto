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
use \Symfony\Bundle\SecurityBundle\Security;

class ApiSuscripcionController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/subscribe', name: 'subscribe_user', methods: ['POST'])]
    public function subscribe(Request $request, SuscripcionRepository $suscripcionRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $suscripcionId = $data['suscripcionId'] ?? null;

        if (!$suscripcionId) {
            return new JsonResponse(['error' => 'No subscription ID provided'], 400);
        }

        $suscripcion = $suscripcionRepository->find($suscripcionId);
        if (!$suscripcion) {
            return new JsonResponse(['error' => 'Subscription not found'], 404);
        }

        $user->setSuscripcion($suscripcion);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['success' => 'Subscription updated'], 200);
    }

    #[Route('/api/suscripciones', name: 'app_api_suscripciones', methods: ['GET'])]
    public function getSubscriptions(SuscripcionRepository $suscripcionRepository): JsonResponse
    {
        $suscripciones = $suscripcionRepository->findAll();

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

        return new JsonResponse($data);
    }
}
