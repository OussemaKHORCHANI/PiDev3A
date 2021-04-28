<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Joueurs
 *
 * @ORM\Table(name="joueurs", indexes={@ORM\Index(name="fk_clubs", columns={"id_club"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\JoueursRepository")
 */
class Joueurs
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
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     *  @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "Le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom ne doit pas dépasser {{ limit }} caractères"
     *
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "Le prenom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le prenom ne doit pas dépasser {{ limit }} caractères"
     *
     * )
     */
    private $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     * @Assert\GreaterThan  (
     *     value = 10,
     *     message = "L'age doit dépasser 10 ans"
     * )
     * @Assert\LessThan(
     *     value = 25,
     *     message = "L'age ne doit pas dépasser 25 ans"
     * )
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_club", type="string", length=11, nullable=true)
     */
    private $nomClub;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     * @Assert\Email(
     *       message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotBlank
     * (
     * message = "remplissez le champ SVP"
     * )
     *
     */
    private $email;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_club", referencedColumnName="id_club")
     * })
     */
    private $idClub;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getNomClub(): ?string
    {
        return $this->nomClub;
    }

    public function setNomClub(?string $nomClub): self
    {
        $this->nomClub = $nomClub;

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

    public function getIdClub(): ?Club
    {
        return $this->idClub;
    }

    public function setIdClub(?Club $idClub): self
    {
        $this->idClub = $idClub;

        return $this;
    }



}
