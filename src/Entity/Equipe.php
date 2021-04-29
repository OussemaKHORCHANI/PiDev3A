<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\EquipeRepository")
 */
class Equipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="idequipe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idequipe;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="le champs vide")
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer", nullable=false)
     *@Assert\NotBlank(message="le champs vide")
     */
    private $nombre;

    public function getIdequipe(): ?int
    {
        return $this->idequipe;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


}
