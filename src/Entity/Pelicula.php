<?php

namespace App\Entity;

use App\Repository\PeliculaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeliculaRepository::class)]
class Pelicula
{
    // Identificador único de la entidad, se genera automáticamente
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Título de la película, con una longitud máxima de 50 caracteres
    #[ORM\Column(length: 50)]
    private ?string $titulo = null;

    // Director de la película, con una longitud máxima de 100 caracteres
    #[ORM\Column(length: 100)]
    private ?string $director = null;

    // Categoría de la película, con una longitud máxima de 20 caracteres
    #[ORM\Column(length: 20)]
    private ?string $categoria = null;

    // Duración de la película en minutos
    #[ORM\Column]
    private ?int $duracion = null;

    // Sinopsis de la película, puede ser nula, con una longitud máxima de 255 caracteres
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sinopsis = null;

    // Imagen de la película, con una longitud máxima de 100 caracteres
    #[ORM\Column(length: 100)]
    private ?string $imagen = null;

    // Relación uno-a-muchos con la entidad Reproduccion, mapeada por la propiedad 'pelicula' en Reproduccion
    #[ORM\OneToMany(mappedBy: 'pelicula', targetEntity: Reproduccion::class)]
    private Collection $reproduccion;

    // ID de la carpeta donde se encuentra la película, puede ser nulo
    #[ORM\Column(nullable: true)]
    private ?int $carpeta = null;

    // Video de la película, con una longitud máxima de 255 caracteres
    #[ORM\Column(length: 255)]
    private ?string $video = null;

    // Constructor para inicializar la colección de reproducciones
    public function __construct()
    {
        $this->reproduccion = new ArrayCollection();
    }

    // Obtener el ID de la película
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtener el título de la película
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    // Establecer el título de la película
    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    // Obtener el director de la película
    public function getDirector(): ?string
    {
        return $this->director;
    }

    // Establecer el director de la película
    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    // Obtener la categoría de la película
    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    // Establecer la categoría de la película
    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    // Obtener la duración de la película
    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    // Establecer la duración de la película
    public function setDuracion(int $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }

    // Obtener la sinopsis de la película
    public function getSinopsis(): ?string
    {
        return $this->sinopsis;
    }

    // Establecer la sinopsis de la película
    public function setSinopsis(?string $sinopsis): static
    {
        $this->sinopsis = $sinopsis;

        return $this;
    }

    // Obtener la imagen de la película
    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    // Establecer la imagen de la película
    public function setImagen(?string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection<int, Reproduccion>
     */
    public function getReproduccion(): Collection
    {
        return $this->reproduccion;
    }

    // Añadir una reproducción a la película
    public function addReproduccion(Reproduccion $reproduccion): static
    {
        if (!$this->reproduccion->contains($reproduccion)) {
            $this->reproduccion->add($reproduccion);
            $reproduccion->setPelicula($this);
        }

        return $this;
    }

    // Eliminar una reproducción de la película
    public function removeReproduccion(Reproduccion $reproduccion): static
    {
        if ($this->reproduccion->removeElement($reproduccion)) {
            // Establecer el lado propietario a null, a menos que ya esté cambiado
            if ($reproduccion->getPelicula() === $this) {
                $reproduccion->setPelicula(null);
            }
        }

        return $this;
    }

    // Obtener el ID de la carpeta
    public function getCarpeta(): ?int
    {
        return $this->carpeta;
    }

    // Establecer el ID de la carpeta
    public function setCarpeta(?int $carpeta): static
    {
        $this->carpeta = $carpeta;

        return $this;
    }

    // Obtener el video de la película
    public function getVideo(): ?string
    {
        return $this->video;
    }

    // Establecer el video de la película
    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }
}
