<?php

namespace App\Entity;

use App\Repository\PeliculaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeliculaRepository::class)]
class Pelicula
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titulo = null;

    #[ORM\Column(length: 100)]
    private ?string $director = null;

    #[ORM\Column(length: 20)]
    private ?string $categoria = null;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sinopsis = null;

    #[ORM\Column(length: 100)]
    private ?string $imagen = null;

    #[ORM\OneToMany(mappedBy: 'pelicula', targetEntity: Reproduccion::class)]
    private Collection $reproduccion;

    #[ORM\Column(nullable: true)]
    private ?int $carpeta = null;

    #[ORM\Column(length: 255)]
    private ?string $video = null;

    public function __construct()
    {
        $this->reproduccion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getSinopsis(): ?string
    {
        return $this->sinopsis;
    }

    public function setSinopsis(?string $sinopsis): static
    {
        $this->sinopsis = $sinopsis;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

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

    public function addReproduccion(Reproduccion $reproduccion): static
    {
        if (!$this->reproduccion->contains($reproduccion)) {
            $this->reproduccion->add($reproduccion);
            $reproduccion->setPelicula($this);
        }

        return $this;
    }

    public function removeReproduccion(Reproduccion $reproduccion): static
    {
        if ($this->reproduccion->removeElement($reproduccion)) {
            // set the owning side to null (unless already changed)
            if ($reproduccion->getPelicula() === $this) {
                $reproduccion->setPelicula(null);
            }
        }

        return $this;
    }

    public function getCarpeta(): ?int
    {
        return $this->carpeta;
    }

    public function setCarpeta(?int $carpeta): static
    {
        $this->carpeta = $carpeta;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }
}