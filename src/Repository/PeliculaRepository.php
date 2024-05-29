<?php

namespace App\Repository;

use App\Entity\Pelicula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PeliculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pelicula::class);
    }

    public function findPopularMovies(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->where('p.carpeta = :carpeta')
            ->setParameter('carpeta', 1)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getArrayResult();
    }

    public function findRecommendedMovies(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->where('p.carpeta = :carpeta')
            ->setParameter('carpeta', 2)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getArrayResult();
    }

    public function findMovieById(int $id): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.director, p.categoria, p.duracion, p.sinopsis, p.imagen, p.video')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMoviesByTitle(string $title): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.director, p.categoria, p.duracion, p.sinopsis, p.imagen, p.video')
            ->where('p.titulo LIKE :titulo')
            ->setParameter('titulo', '%' . $title . '%')
            ->getQuery()
            ->getArrayResult();
    }

    public function findWatchedMovies(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.imagen')
            ->join('p.reproduccion', 'r')
            ->where('r.user = :user')
            ->setParameter('user', $userId)
            ->orderBy('r.watchedDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }
}
