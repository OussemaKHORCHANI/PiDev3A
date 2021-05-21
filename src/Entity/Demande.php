<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Demande
 *
 * @ORM\Table(name="demande")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DemandeRepository")
 */
class Demande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=200, nullable=false)
     * @Assert\Date(message="date invalide")
     * @Assert\NotBlank(message="le champs vide")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="nomterrain", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="le champs vide")
     */
    private $nomterrain;

    /**
     * @var string
     *
     * @ORM\Column(name="nomequipe", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="le champs vide")
     */
    private $nomequipe;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="le champs vide")
     * @Assert\Email(message="email invalide")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=200, nullable=false)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNomterrain(): ?string
    {
        return $this->nomterrain;
    }

    public function setNomterrain(string $nomterrain): self
    {
        $this->nomterrain = $nomterrain;

        return $this;
    }

    public function getNomequipe(): ?string
    {
        return $this->nomequipe;
    }

    public function setNomequipe(string $nomequipe): self
    {
        $this->nomequipe = $nomequipe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


}
