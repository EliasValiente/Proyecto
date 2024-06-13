<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identificador único de la entidad, se genera automáticamente
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email del usuario, debe ser único
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Roles del usuario, almacenados como un array
    #[ORM\Column]
    private array $roles = [];

    // Contraseña hasheada del usuario
    #[ORM\Column]
    private ?string $password = null;

    // Nombre del usuario
    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    // Nombre de usuario, debe ser único
    #[ORM\Column(length: 50)]
    private ?string $username = null;

    // Apellidos del usuario
    #[ORM\Column(length: 75)]
    private ?string $apellidos = null;

    // CVV de la tarjeta del usuario, puede ser nulo
    #[ORM\Column(nullable: true)]
    private ?string $cvv = null;

    // Número de tarjeta del usuario, puede ser nulo
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $tarjeta = null;
    
    // Fecha de validez de la tarjeta del usuario, puede ser nula
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaValidez = null;

    // Relación uno-a-muchos con la entidad ListaSeguimiento, con eliminación en cascada
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ListaSeguimiento::class, orphanRemoval: true)]
    private Collection $listasseguimiento;

    // Relación muchos-a-uno con la entidad Suscripcion
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Suscripcion $suscripcion = null;

    // Relación uno-a-muchos con la entidad Reproduccion
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reproduccion::class)]
    private Collection $reproducciones;

    // Constructor para inicializar las colecciones de listas de seguimiento y reproducciones
    public function __construct()
    {
        $this->listasseguimiento = new ArrayCollection();
        $this->reproducciones = new ArrayCollection();
    }

    // Obtener el ID del usuario
    public function getId(): ?int
    {
        return $this->id;
    }

    // Obtener el email del usuario
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Establecer el email del usuario
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Un identificador visual que representa a este usuario.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantizar que cada usuario tenga al menos ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si almacenas datos sensibles temporales en el usuario, límpialos aquí
        // $this->plainPassword = null;
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

    public function getUserName(): ?string
    {
        return $this->username;
    }

    public function setUserName(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getTarjeta(): ?string
    {
        return $this->tarjeta;
    }

    public function setTarjeta(?string $tarjeta): static
    {
        $this->tarjeta = $tarjeta;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getFechaValidez(): ?\DateTimeInterface
    {
        return $this->fechaValidez;
    }

    public function setFechaValidez(?\DateTimeInterface $fechaValidez): static
    {
        $this->fechaValidez = $fechaValidez;

        return $this;
    }

    /**
     * @return Collection<int, ListaSeguimiento>
     */
    public function getListasseguimiento(): Collection
    {
        return $this->listasseguimiento;
    }

    public function addListasseguimiento(ListaSeguimiento $listasseguimiento): static
    {
        if (!$this->listasseguimiento->contains($listasseguimiento)) {
            $this->listasseguimiento->add($listasseguimiento);
            $listasseguimiento->setUser($this);
        }

        return $this;
    }

    public function removeListasseguimiento(ListaSeguimiento $listasseguimiento): static
    {
        if ($this->listasseguimiento->removeElement($listasseguimiento)) {
            // Establecer el lado propietario a null, a menos que ya esté cambiado
            if ($listasseguimiento->getUser() === $this) {
                $listasseguimiento->setUser(null);
            }
        }

        return $this;
    }

    public function getSuscripcion(): ?Suscripcion
    {
        return $this->suscripcion;
    }

    public function setSuscripcion(?Suscripcion $suscripcion): static
    {
        $this->suscripcion = $suscripcion;

        return $this;
    }

    /**
     * @return Collection<int, Reproduccion>
     */
    public function getReproducciones(): Collection
    {
        return $this->reproducciones;
    }

    public function addReproduccion(Reproduccion $reproduccione): static
    {
        if (!$this->reproducciones->contains($reproduccione)) {
            $this->reproducciones->add($reproduccione);
            $reproduccione->setUser($this);
        }

        return $this;
    }

    public function removeReproduccion(Reproduccion $reproduccione): static
    {
        if ($this->reproducciones->removeElement($reproduccione)) {
            // Establecer el lado propietario a null, a menos que ya esté cambiado
            if ($reproduccione->getUser() === $this) {
                $reproduccione->setUser(null);
            }
        }

        return $this;
    }
}
