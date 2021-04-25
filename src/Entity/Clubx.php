<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clubx
 *
 * @ORM\Table(name="clubx", indexes={@ORM\Index(name="clubet", columns={"id_club"}), @ORM\Index(name="joueuret", columns={"id"})})
 * @ORM\Entity
 */
class Clubx
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_clubx", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idClubx;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_club", type="string", length=30, nullable=false)
     */
    private $nomClub;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_joueur", type="string", length=30, nullable=false)
     */
    private $nomJoueur;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_club", referencedColumnName="id_club")
     * })
     */
    private $idClub;

    /**
     * @var \Joueurs
     *
     * @ORM\ManyToOne(targetEntity="Joueurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    public function getIdClubx(): ?int
    {
        return $this->idClubx;
    }

    public function getNomClub(): ?string
    {
        return $this->nomClub;
    }

    public function setNomClub(string $nomClub): self
    {
        $this->nomClub = $nomClub;

        return $this;
    }

    public function getNomJoueur(): ?string
    {
        return $this->nomJoueur;
    }

    public function setNomJoueur(string $nomJoueur): self
    {
        $this->nomJoueur = $nomJoueur;

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

    public function getId(): ?Joueurs
    {
        return $this->id;
    }

    public function setId(?Joueurs $id): self
    {
        $this->id = $id;

        return $this;
    }


}
