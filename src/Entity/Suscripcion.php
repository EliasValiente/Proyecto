<?php

namespace App\Entity;

use App\Repository\SuscripcionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuscripcionRepository::class)]
class Suscripcion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'suscripcion', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column]
    private ?float $precio_mensual = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $caracteristicas = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSuscripcion($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSuscripcion() === $this) {
                $user->setSuscripcion(null);
            }
        }

        return $this;
    }

    public function getPrecioMensual(): ?float
    {
        return $this->precio_mensual;
    }

    public function setPrecioMensual(float $precio_mensual): static
    {
        $this->precio_mensual = $precio_mensual;

        return $this;
    }

    public function getCaracteristicas(): ?array
    {
        return $this->caracteristicas;
    }

    public function setCaracteristicas(?array $caracteristicas): static
    {
        $this->caracteristicas = $caracteristicas;

        return $this;
    }
}