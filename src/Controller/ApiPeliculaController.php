<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PeliculaRepository;
use App\Repository\ReproduccionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApiPeliculaController extends AbstractController
{
    private $peliculaRepository;
    private $reproduccionRepository;
    private $security;

    // Constructor para inicializar los repositorios y el servicio de seguridad
    public function __construct(PeliculaRepository $peliculaRepository, ReproduccionRepository $reproduccionRepository, Security $security)
    {
        $this->peliculaRepository = $peliculaRepository;
        $this->reproduccionRepository = $reproduccionRepository;
        $this->security = $security;
    }

    #[Route('/api/movies/popular', name: 'api_popular_movies', methods:['GET'])]
    public function getPopularMovies(): JsonResponse
    {
        // Obtener las películas populares del repositorio
        $movies = $this->peliculaRepository->findPopularMovies();
        // Devolver las películas en formato JSON
        return $this->json($movies);
    }

    #[Route('/api/movies/recommended', name: 'api_recommended_movies', methods:['GET'])]
    public function getRecommendedMovies(): JsonResponse
    {
        // Obtener las películas recomendadas del repositorio
        $movies = $this->peliculaRepository->findRecommendedMovies();
        // Devolver las películas en formato JSON
        return $this->json($movies);
    }

    #[Route('/api/movies/watched', name: 'api_watched_movies', methods: ['GET'])]
    public function getWatchedMovies(): JsonResponse
    {
        // Obtener el usuario autenticado
        $user = $this->getUser();
        
        // Verificar si el usuario no está autenticado
        if (!$user instanceof User) {
            // Devolver un error 401 si el usuario no está autenticado
            return $this->json(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Obtener las películas vistas por el usuario del repositorio
        $movies = $this->reproduccionRepository->findWatchedMoviesByUser($user->getId());
        // Devolver las películas en formato JSON
        return $this->json($movies);
    }

    #[Route('/api/movies/title', name: 'api_searched_movies', methods: ['GET'])]
    public function getSearchedMovies(Request $request): JsonResponse
    {
        // Obtener el título de la película de los parámetros de la solicitud
        $title = $request->query->get('title', '');
        // Buscar las películas por título en el repositorio
        $movies = $this->peliculaRepository->findMoviesByTitle($title);
        // Devolver las películas en formato JSON
        return $this->json($movies);
    }

    #[Route('/api/movies/{id}', name:"api_movie_by_id", methods:['GET'])]
    public function getMovieById(int $id): JsonResponse
    {
        // Buscar la película por ID en el repositorio
        $movie = $this->peliculaRepository->findMovieById($id);
        if ($movie) {
            // Devolver la película en formato JSON si se encuentra
            return $this->json($movie);
        } else {
            // Devolver un mensaje de error 404 si la película no se encuentra
            return $this->json(['message' => 'Movie not found'], 404);
        }
    }

}
