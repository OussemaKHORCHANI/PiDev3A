<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
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
     * @var string|null
     *
     * @ORM\Column(name="categories_id", type="string", length=255, nullable=true)
     */
    private $categoriesId;



    private $type;
    /**
     * @var string
     * * @Assert\NotBlank(message = "remplissez le champ SVP")
     *  @Assert\Length(min = 3,max = 20,minMessage = "Le nom doit comporter au moins {{ limit }} caractères",maxMessage = "Le nom ne doit pas dépasser {{ limit }} caractères")
     * @ORM\Column(name="Nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string|null
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
     * @ORM\Column(name="Description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string|null
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
     * @ORM\Column(name="Lieu_event", type="string", length=255, nullable=true)
     */
    private $lieuEvent;

    /**
     * @var \DateTime|null
      * @Assert\Range(min = "now", minMessage ="la date doit etre apres la date d'aujourdhui")
     * @ORM\Column(name="Date_event", type="date", nullable=true)
     */
    private $dateEvent;

    /**
     * @var float|null
    *@Assert\Regex(pattern="/^[0-9]*$/", message="number_only")
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var int|null
     * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only")
     * @ORM\Column(name="etat", type="integer", nullable=true)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoriesId()
    {
        return $this->categoriesId;
    }

    public function setCategoriesId( $categoriesId)
    {
        $this->categoriesId = $categoriesId;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieuEvent(): ?string
    {
        return $this->lieuEvent;
    }

    public function setLieuEvent(?string $lieuEvent): self
    {
        $this->lieuEvent = $lieuEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(?\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $Type): self
    {
        $this->type = $Type;

        return $this;
    }




}
