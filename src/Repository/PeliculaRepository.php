<?php

namespace App\Repository;

use App\Entity\Pelicula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PeliculaRepository extends ServiceEntityRepository
{
    // Constructor que inicializa el repositorio con el registro de gestión
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pelicula::class);
    }

    // Método para encontrar las películas populares
    public function findPopularMovies(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->where('p.carpeta = :carpeta')  // Filtrar por carpeta 1 (películas populares)
            ->setParameter('carpeta', 1)
            ->orderBy('p.id', 'DESC')  // Ordenar por ID descendente
            ->setMaxResults(8)  // Limitar el resultado a 8 películas
            ->getQuery()
            ->getArrayResult();  // Obtener el resultado como un array
    }

    // Método para encontrar las películas recomendadas
    public function findRecommendedMovies(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->where('p.carpeta = :carpeta')  // Filtrar por carpeta 2 (películas recomendadas)
            ->setParameter('carpeta', 2)
            ->orderBy('p.id', 'DESC')  // Ordenar por ID descendente
            ->setMaxResults(8)  // Limitar el resultado a 8 películas
            ->getQuery()
            ->getArrayResult();  // Obtener el resultado como un array
    }

    // Método para encontrar una película por su ID
    public function findMovieById(int $id): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.director, p.categoria, p.duracion, p.sinopsis, p.imagen, p.video')
            ->where('p.id = :id')  // Filtrar por ID
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();  // Obtener un solo resultado o null si no se encuentra
    }

    // Método para encontrar películas por título
    public function findMoviesByTitle(string $title): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.director, p.categoria, p.duracion, p.sinopsis, p.imagen, p.video')
            ->where('p.titulo LIKE :titulo')  // Filtrar por título utilizando LIKE para búsquedas parciales
            ->setParameter('titulo', '%' . $title . '%')
            ->getQuery()
            ->getArrayResult();  // Obtener el resultado como un array
    }

    // Método para encontrar las películas vistas por un usuario
    public function findWatchedMovies(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->join('p.reproduccion', 'r')  // Unir con la tabla de reproducciones
            ->where('r.user = :user')  // Filtrar por el ID del usuario
            ->setParameter('user', $userId)
            ->orderBy('r.watchedDate', 'DESC')  // Ordenar por la fecha de reproducción descendente
            ->setMaxResults(10)  // Limitar el resultado a 10 películas
            ->getQuery()
            ->getArrayResult();  // Obtener el resultado como un array
    }
}
