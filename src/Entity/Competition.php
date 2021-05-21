<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CompetitionRepository")
 */
class Competition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_competition", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCompetition;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_competition", type="string", length=20, nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     */
    private $nomCompetition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     * @Assert\Expression("this.getDateDebut() < this.getDateFin()",message= "verifier les dates ")
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     * @Assert\GreaterThan("today UTC")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     * @Assert\Expression(
     * "this.getDateDebut() < this.getDateFin()",
     *     message= "verifier les dates "
     * )
     *
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     */
    private $dateFin;

    public function getIdCompetition(): ?int
    {
        return $this->idCompetition;
    }

    public function getNomCompetition(): ?string
    {
        return $this->nomCompetition;
    }

    public function setNomCompetition(string $nomCompetition): self
    {
        $this->nomCompetition = $nomCompetition;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }


}
