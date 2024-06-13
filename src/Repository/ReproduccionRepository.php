<?php

namespace App\Repository;

use App\Entity\Reproduccion;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reproduccion>
 *
 * @method Reproduccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reproduccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reproduccion[]    findAll()
 * @method Reproduccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReproduccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reproduccion::class);
    }

    // Método para encontrar las reproducciones de hoy
    public function findByTodayDate(): array {
        $hoy = date("y/m/d");

        $qb = $this->createQueryBuilder('r')
            ->select('p.titulo as tituloPelicula', 'COUNT(r.id) as cantidadReproducciones')
            ->leftJoin('r.pelicula', 'p')
            ->where('r.fecha = :hoy')
            ->setParameter('hoy', $hoy)
            ->groupBy('p.id'); // Agrupa por el id de la entidad Pelicula

        return $qb->getQuery()->getResult();
    }

    // Método para encontrar las películas más vistas
    public function findMasVistas(): array {
        $qb = $this->createQueryBuilder('r')
            ->select('p.titulo as tituloPelicula', 'COUNT(r.id) as cantidadReproducciones')
            ->leftJoin('r.pelicula', 'p')
            ->groupBy('p.id'); // Agrupa por el id de la entidad Pelicula

        return $qb->getQuery()->getResult();
    }

    // Método para encontrar las películas vistas por un usuario específico
    public function findWatchedMoviesByUser($userId): array {
        $qb = $this->createQueryBuilder('r')
            ->select('p.id, p.titulo, p.director, p.categoria, p.duracion, p.sinopsis, p.imagen, p.carpeta, p.video')
            ->leftJoin('r.pelicula', 'p')
            ->where('r.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('p.id'); // Agrupa por el id de la entidad Pelicula

        return $qb->getQuery()->getResult();
    }
}
