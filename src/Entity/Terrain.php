<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Terrain
 *
 * @ORM\Table(name="terrain")
 * @ORM\Entity(repositoryClass="App\Repository\TerrainRepository")
 */
class Terrain
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTerrain", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idterrain;

    /**
     * @var string
     *
     * @ORM\Column(name="nomTerrain", type="string", length=30, nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "le nom du terrain doit comporter au moins  {{ limit }} caractères")
     * @Assert\NotBlank(message="le nom de terrain est obligatoire")
     */
    private $nomterrain;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=30, nullable=false)
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "l'adresse doit comporter au moins  {{ limit }} caractères")
     * @Assert\NotBlank(message="l'adresse de terrain est obligatoire")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=50, nullable=false)
     * @Assert\Choice({"disponible", "réservé"})
     */
    private $etat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     *  @Assert\NotBlank(message="etat est obligatoire")
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="text", length=65535, nullable=true)
     * @Assert\Url
     */
    private $photo;

    public function getIdterrain(): ?int
    {
        return $this->idterrain;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomterrain();
    }


}
