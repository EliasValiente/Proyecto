<?php

namespace App\Entity;

use App\Repository\ReproduccionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReproduccionRepository::class)]
class Reproduccion
{
    // Identificador único de la entidad, se genera automáticamente
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Fecha de la reproducción, utilizando el tipo de dato DATE_MUTABLE
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    // Relación muchos-a-uno con la entidad User
    #[ORM\ManyToOne(inversedBy: 'reproducciones')]
    private ?User $user = null;

    // Relación muchos-a-uno con la entidad Pelicula
    #[ORM\ManyToOne(inversedBy: 'reproduccion')]
    private ?Pelicula $pelicula = null;

    // Obtener el ID de la reproducción
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtener la fecha de la reproducción
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    // Establecer la fecha de la reproducción
    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    // Obtener el usuario que realizó la reproducción
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Establecer el usuario que realizó la reproducción
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Obtener la película que fue reproducida
    public function getPelicula(): ?Pelicula
    {
        return $this->pelicula;
    }

    // Establecer la película que fue reproducida
    public function setPelicula(?Pelicula $pelicula): static
    {
        $this->pelicula = $pelicula;

        return $this;
    }
}
