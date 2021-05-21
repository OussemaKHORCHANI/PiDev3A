<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_reservation", columns={"idTerrain"}), @ORM\Index(name="fkClient", columns={"idclient"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRes", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("terrain : read")
     */
    private $idres;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\GreaterThanOrEqual("today UTC")
     * @Groups("terrain : read")
     */
    private $date;

    /**
     * @var \DateTime
     * @ORM\Column(name="heureDebut", type="time", nullable=false)
     * @Groups("terrain : read")
     */
    private $heuredebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureFin", type="time", nullable=false)
     *@Groups("terrain : read")
     */
    private $heurefin;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idclient", referencedColumnName="idC")
     * })
     */
    private $idclient;

    /**
     * @var \Terrain
     *
     * @ORM\ManyToOne(targetEntity="Terrain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTerrain", referencedColumnName="idTerrain")
     * })
     *@Assert\NotBlank(message="le nom de terrain est obligatoire")
     */
    private $idterrain;

    public function getIdres(): ?int
    {
        return $this->idres;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeuredebut(): ?\DateTime
    {
        return $this->heuredebut;
    }

    public function setHeuredebut(?\DateTime $heuredebut): self
    {
        $this->heuredebut = $heuredebut;

        return $this;
    }

    public function getHeurefin(): ?\DateTime
    {
        return $this->heurefin;
    }

    public function setHeurefin(?\DateTime $heurefin): self
    {
        $this->heurefin = $heurefin;

        return $this;
    }

    public function getIdclient(): ?Client
    {
        return $this->idclient;
    }

    public function setIdclient(?Client $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getIdterrain(): ?Terrain
    {
        return $this->idterrain;
    }

    public function setIdterrain(?Terrain $idterrain): self 
    {
        $this->idterrain = $idterrain;

        return $this;
    }


}
