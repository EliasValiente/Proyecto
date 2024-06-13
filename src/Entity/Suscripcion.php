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
    // Identificador único de la entidad, se genera automáticamente
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Nombre de la suscripción, con una longitud máxima de 50 caracteres
    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    // Duración de la suscripción en meses
    #[ORM\Column]
    private ?int $duracion = null;

    // Precio total de la suscripción
    #[ORM\Column]
    private ?float $precio = null;

    // Descripción de la suscripción, puede ser nula, con una longitud máxima de 150 caracteres
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $descripcion = null;

    // Relación uno-a-muchos con la entidad User, mapeada por la propiedad 'suscripcion' en User
    #[ORM\OneToMany(mappedBy: 'suscripcion', targetEntity: User::class)]
    private Collection $users;

    // Precio mensual de la suscripción
    #[ORM\Column]
    private ?float $precio_mensual = null;

    // Características de la suscripción, almacenadas como una matriz simple
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $caracteristicas = null;

    // Constructor para inicializar la colección de usuarios
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    // Obtener el ID de la suscripción
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtener el nombre de la suscripción
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    // Establecer el nombre de la suscripción
    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    // Obtener la duración de la suscripción
    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    // Establecer la duración de la suscripción
    public function setDuracion(int $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }

    // Obtener el precio de la suscripción
    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    // Establecer el precio de la suscripción
    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    // Obtener la descripción de la suscripción
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    // Establecer la descripción de la suscripción
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

    // Añadir un usuario a la suscripción
    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSuscripcion($this);
        }

        return $this;
    }

    // Eliminar un usuario de la suscripción
    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // Establecer el lado propietario a null, a menos que ya esté cambiado
            if ($user->getSuscripcion() === $this) {
                $user->setSuscripcion(null);
            }
        }

        return $this;
    }

    // Obtener el precio mensual de la suscripción
    public function getPrecioMensual(): ?float
    {
        return $this->precio_mensual;
    }

    // Establecer el precio mensual de la suscripción
    public function setPrecioMensual(float $precio_mensual): static
    {
        $this->precio_mensual = $precio_mensual;

        return $this;
    }

    // Obtener las características de la suscripción
    public function getCaracteristicas(): ?array
    {
        return $this->caracteristicas;
    }

    // Establecer las características de la suscripción
    public function setCaracteristicas(?array $caracteristicas): static
    {
        $this->caracteristicas = $caracteristicas;

        return $this;
    }
}
