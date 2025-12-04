<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nombreApellidos = null;

    #[ORM\Column(nullable: true)]
    private ?bool $propietario = null;

    /**
     * @var Collection<int, Alojamiento>
     */
    #[ORM\OneToMany(targetEntity: Alojamiento::class, mappedBy: 'propietario')]
    private Collection $alojamientos;

    /**
     * @var Collection<int, Alquiler>
     */
    #[ORM\OneToMany(targetEntity: Alquiler::class, mappedBy: 'cliente')]
    private Collection $Alquileres;

    public function __construct()
    {
        $this->alojamientos = new ArrayCollection();
        $this->Alquileres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
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
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombreApellidos(): ?string
    {
        return $this->nombreApellidos;
    }

    public function setNombreApellidos(string $nombreApellidos): static
    {
        $this->nombreApellidos = $nombreApellidos;

        return $this;
    }

    public function isPropietario(): ?bool
    {
        return $this->propietario;
    }

    public function setPropietario(?bool $propietario): static
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * @return Collection<int, Alojamiento>
     */
    public function getAlojamientos(): Collection
    {
        return $this->alojamientos;
    }

    public function addAlojamiento(Alojamiento $alojamiento): static
    {
        if (!$this->alojamientos->contains($alojamiento)) {
            $this->alojamientos->add($alojamiento);
            $alojamiento->setPropietario($this);
        }

        return $this;
    }

    public function removeAlojamiento(Alojamiento $alojamiento): static
    {
        if ($this->alojamientos->removeElement($alojamiento)) {
            // set the owning side to null (unless already changed)
            if ($alojamiento->getPropietario() === $this) {
                $alojamiento->setPropietario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alquiler>
     */
    public function getAlquileres(): Collection
    {
        return $this->Alquileres;
    }

    public function addAlquiler(Alquiler $alquilere): static
    {
        if (!$this->Alquileres->contains($alquilere)) {
            $this->Alquileres->add($alquilere);
            $alquilere->setCliente($this);
        }

        return $this;
    }

    public function removeAlquiler(Alquiler $alquilere): static
    {
        if ($this->Alquileres->removeElement($alquilere)) {
            // set the owning side to null (unless already changed)
            if ($alquilere->getCliente() === $this) {
                $alquilere->setCliente(null);
            }
        }

        return $this;
    }
}
