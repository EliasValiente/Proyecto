<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/test', name: 'app_api', methods:['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'CORS is working!']);
    }
}
