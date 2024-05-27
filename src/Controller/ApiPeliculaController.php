<?php

namespace App\Controller;

use App\Repository\PeliculaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiPeliculaController extends AbstractController
{

    private $peliculaRepository;

    public function __construct(PeliculaRepository $peliculaRepository)
    {
        $this->peliculaRepository = $peliculaRepository;
    }

    #[Route('/api/movies/popular', name: 'api_popular_movies', methods:['GET'])]
    public function getPopularMovies(): JsonResponse
    {
        $movies = $this->peliculaRepository->findPopularMovies();
        return $this->json($movies);
    }

    #[Route('/api/movies/recommended', name: 'api_recommended_movies', methods:['GET'])]
    public function getRecommendedMovies(): JsonResponse
    {
        $movies = $this->peliculaRepository->findRecommendedMovies();
        return $this->json($movies);
    }

    
    #[Route('/api/movies/{id}', name:"api_movie_by_id", methods:['GET'])]
    public function getMovieById(int $id): JsonResponse
    {
        $movie = $this->peliculaRepository->findMovieById($id);
        if ($movie) {
            return $this->json($movie);
        } else {
            return $this->json(['message' => 'Movie not found'], 404);
        }
    }

}
