<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clubcomp
 *
 * @ORM\Table(name="clubcomp", indexes={@ORM\Index(name="fk_club", columns={"id_club"}), @ORM\Index(name="fk_comp", columns={"id_competition"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ClubcompRepository")
 */
class Clubcomp
{
    /**
     * @var int
     *
     * @ORM\Column(name="idclubcomp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idclubcomp;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_club", type="string", length=20, nullable=false)
     */
    private $nomClub;

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
     * @var \Competition
     *
     * @ORM\ManyToOne(targetEntity="Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_competition", referencedColumnName="id_competition")
     * })
     */
    private $idCompetition;

    public function getIdclubcomp(): ?int
    {
        return $this->idclubcomp;
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

    public function getIdClub(): ?Club
    {
        return $this->idClub;
    }

    public function setIdClub(?Club $idClub): self
    {
        $this->idClub = $idClub;

        return $this;
    }

    public function getIdCompetition(): ?Competition
    {
        return $this->idCompetition;
    }

    public function setIdCompetition(?Competition $idCompetition): self
    {
        $this->idCompetition = $idCompetition;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getIdclubcomp();
    }

}
