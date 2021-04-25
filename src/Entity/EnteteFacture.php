<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnteteFacture
 *
 * @ORM\Table(name="entete_facture")
 * @ORM\Entity
 */
class EnteteFacture
{
    /**
     * @var int
     *
     * @ORM\Column(name="num_piece", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numPiece;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=5, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_exp", type="date", nullable=false)
     */
    private $dateExp;

    /**
     * @var int
     *
     * @ORM\Column(name="tier", type="integer", nullable=false)
     */
    private $tier;

    /**
     * @var int
     *
     * @ORM\Column(name="ref_facture", type="integer", nullable=false)
     */
    private $refFacture;

    public function getNumPiece(): ?int
    {
        return $this->numPiece;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->dateExp;
    }

    public function setDateExp(\DateTimeInterface $dateExp): self
    {
        $this->dateExp = $dateExp;

        return $this;
    }

    public function getTier(): ?int
    {
        return $this->tier;
    }

    public function setTier(int $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getRefFacture(): ?int
    {
        return $this->refFacture;
    }

    public function setRefFacture(int $refFacture): self
    {
        $this->refFacture = $refFacture;

        return $this;
    }


}
