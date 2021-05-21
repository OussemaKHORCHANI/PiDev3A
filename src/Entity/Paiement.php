<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement", indexes={@ORM\Index(name="fk_paiement", columns={"num_piece"})})
 * @ORM\Entity
 */
class Paiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idP", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idp;

    /**
     * @var string
     *
     * @ORM\Column(name="Ref", type="string", length=50, nullable=false)
     */
    private $ref;

    /**
     * @var int
     *
     * @ORM\Column(name="num_ced", type="integer", nullable=false)
     */
    private $numCed;

    /**
     * @var int
     *
     * @ORM\Column(name="num_cb", type="integer", nullable=false)
     */
    private $numCb;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var \EnteteFacture
     *
     * @ORM\ManyToOne(targetEntity="EnteteFacture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="num_piece", referencedColumnName="num_piece")
     * })
     */
    private $numPiece;

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getNumCed(): ?int
    {
        return $this->numCed;
    }

    public function setNumCed(int $numCed): self
    {
        $this->numCed = $numCed;

        return $this;
    }

    public function getNumCb(): ?int
    {
        return $this->numCb;
    }

    public function setNumCb(int $numCb): self
    {
        $this->numCb = $numCb;

        return $this;
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

    public function getNumPiece(): ?EnteteFacture
    {
        return $this->numPiece;
    }

    public function setNumPiece(?EnteteFacture $numPiece): self
    {
        $this->numPiece = $numPiece;

        return $this;
    }


}
