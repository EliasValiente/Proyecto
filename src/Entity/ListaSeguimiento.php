<?php

namespace App\Entity;

use App\Repository\ListaSeguimientoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListaSeguimientoRepository::class)]
class ListaSeguimiento
{
    // Identificador único de la entidad, se genera automáticamente
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nombre de la lista de seguimiento, con una longitud máxima de 25 caracteres
    #[ORM\Column(length: 25)]
    private ?string $nombre = null;

    // Descripción de la lista de seguimiento, puede ser nula, con una longitud máxima de 100 caracteres
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $descripcion = null;

    // Relación muchos-a-uno con la entidad User, esta relación no puede ser nula
    #[ORM\ManyToOne(inversedBy: 'listasseguimiento')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Relación muchos-a-muchos con la entidad Pelicula
    #[ORM\ManyToMany(targetEntity: Pelicula::class)]
    private Collection $peliculas;

    // Constructor para inicializar la colección de películas
    public function __construct()
    {
        $this->peliculas = new ArrayCollection();
    }

    // Obtener el ID de la lista de seguimiento
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtener el nombre de la lista de seguimiento
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    // Establecer el nombre de la lista de seguimiento
    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    // Obtener la descripción de la lista de seguimiento
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    // Establecer la descripción de la lista de seguimiento
    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    // Obtener el usuario propietario de la lista de seguimiento
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Establecer el usuario propietario de la lista de seguimiento
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Obtener la colección de películas en la lista de seguimiento
     * @return Collection<int, Pelicula>
     */
    public function getPeliculas(): Collection
    {
        return $this->peliculas;
    }

    // Añadir una película a la lista de seguimiento
    public function addPelicula(Pelicula $pelicula): static
    {
        if (!$this->peliculas->contains($pelicula)) {
            $this->peliculas->add($pelicula);
        }

        return $this;
    }

    // Eliminar una película de la lista de seguimiento
    public function removePelicula(Pelicula $pelicula): static
    {
        $this->peliculas->removeElement($pelicula);

        return $this;
    }
}
