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

    public function __construct(PeliculaRepository $peliculaRepository, ReproduccionRepository $reproduccionRepository, Security $security)
    {
        $this->peliculaRepository = $peliculaRepository;
        $this->reproduccionRepository = $reproduccionRepository;
        $this->security = $security;
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

    #[Route('/api/movies/watched', name: 'api_watched_movies', methods: ['GET'])]
    public function getWatchedMovies(): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user instanceof User) {
            return $this->json(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $movies = $this->reproduccionRepository->findWatchedMoviesByUser($user->getId());
        return $this->json($movies);
    }

    #[Route('/api/movies/title', name: 'api_searched_movies', methods: ['GET'])]
    public function getSearchedMovies(Request $request): JsonResponse
    {
        $title = $request->query->get('title', '');
        $movies = $this->peliculaRepository->findMoviesByTitle($title);
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
