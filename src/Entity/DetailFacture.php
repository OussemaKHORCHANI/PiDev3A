<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetailFacture
 *
 * @ORM\Table(name="detail_facture", indexes={@ORM\Index(name="num_piece", columns={"num_piece"})})
 * @ORM\Entity
 */
class DetailFacture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=30, nullable=false)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="num_piece", type="integer", nullable=false)
     */
    private $numPiece;

    /**
     * @var int
     *
     * @ORM\Column(name="qt", type="integer", nullable=false)
     */
    private $qt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=5, nullable=false)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="ref_article", type="integer", nullable=false)
     */
    private $refArticle;

    /**
     * @var int
     *
     * @ORM\Column(name="ref_facture", type="integer", nullable=false)
     */
    private $refFacture;

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumPiece(): ?int
    {
        return $this->numPiece;
    }

    public function setNumPiece(int $numPiece): self
    {
        $this->numPiece = $numPiece;

        return $this;
    }

    public function getQt(): ?int
    {
        return $this->qt;
    }

    public function setQt(int $qt): self
    {
        $this->qt = $qt;

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

    public function getRefArticle(): ?int
    {
        return $this->refArticle;
    }

    public function setRefArticle(int $refArticle): self
    {
        $this->refArticle = $refArticle;

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
