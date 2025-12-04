<?php

namespace App\Entity;

use App\Repository\AlquilerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlquilerRepository::class)]
class Alquiler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Alquileres')]
    private ?Usuario $cliente = null;

    #[ORM\ManyToOne(inversedBy: 'alquileres')]
    private ?Alojamiento $alojamiento = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente(): ?Usuario
    {
        return $this->cliente;
    }

    public function setCliente(?Usuario $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getAlojamiento(): ?Alojamiento
    {
        return $this->alojamiento;
    }

    public function setAlojamiento(?Alojamiento $alojamiento): static
    {
        $this->alojamiento = $alojamiento;

        return $this;
    }
}
