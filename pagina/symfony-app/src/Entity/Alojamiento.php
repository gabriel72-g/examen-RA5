<?php

namespace App\Entity;

use App\Repository\AlojamientoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlojamientoRepository::class)]
class Alojamiento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'alojamientos')]
    private ?Usuario $propietario = null;

    /**
     * @var Collection<int, Alquiler>
     */
    #[ORM\OneToMany(targetEntity: Alquiler::class, mappedBy: 'alojamiento')]
    private Collection $alquileres;

    public function __construct()
    {
        $this->alquileres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPropietario(): ?Usuario
    {
        return $this->propietario;
    }

    public function setPropietario(?Usuario $propietario): static
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * @return Collection<int, Alquiler>
     */
    public function getAlquileres(): Collection
    {
        return $this->alquileres;
    }

    public function addAlquiler(Alquiler $alquilere): static
    {
        if (!$this->alquileres->contains($alquilere)) {
            $this->alquileres->add($alquilere);
            $alquilere->setAlojamiento($this);
        }

        return $this;
    }

    public function removeAlquiler(Alquiler $alquilere): static
    {
        if ($this->alquileres->removeElement($alquilere)) {
            // set the owning side to null (unless already changed)
            if ($alquilere->getAlojamiento() === $this) {
                $alquilere->setAlojamiento(null);
            }
        }

        return $this;
    }
}
