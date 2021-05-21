<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture", indexes={@ORM\Index(name="fk_facture1", columns={"idRes"})})
 * @ORM\Entity
 */
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="idFacture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfacture;

    /**
     * @var float
     *
     * @ORM\Column(name="sommeFacture", type="float", precision=10, scale=0, nullable=false)
     */
    private $sommefacture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePaiement", type="date", nullable=false)
     */
    private $datepaiement;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRes", referencedColumnName="idRes")
     * })
     */
    private $idres;

    public function getIdfacture(): ?int
    {
        return $this->idfacture;
    }

    public function getSommefacture(): ?float
    {
        return $this->sommefacture;
    }

    public function setSommefacture(float $sommefacture): self
    {
        $this->sommefacture = $sommefacture;

        return $this;
    }

    public function getDatepaiement(): ?\DateTimeInterface
    {
        return $this->datepaiement;
    }

    public function setDatepaiement(\DateTimeInterface $datepaiement): self
    {
        $this->datepaiement = $datepaiement;

        return $this;
    }

    public function getIdres(): ?Reservation
    {
        return $this->idres;
    }

    public function setIdres(?Reservation $idres): self
    {
        $this->idres = $idres;

        return $this;
    }


}
