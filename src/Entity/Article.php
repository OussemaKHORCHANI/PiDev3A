<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 *
 *@ORM\Table(name="article", indexes={@ORM\Index(name="idcat", columns={"idcat"}), @ORM\Index(name="id_article", columns={"id_article"})})
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Entity
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id_article;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=30, nullable=false)
     */
    private $libelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorie", type="string", length=30, nullable=true)
     */
    private $categorie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_article", type="text", length=65535, nullable=true)
     */
    private $imageArticle;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="qt_article", type="integer", nullable=false)
     */
    private $qtArticle;

    /**
     * @var int
     *
     * @ORM\Column(name="ref", type="integer", nullable=false)
     */
    private $ref;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=true)
     */
    private $rate;

    /**
     * @var \Categoriearticle
     *
     * @ORM\ManyToOne(targetEntity="Categoriearticle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcat", referencedColumnName="idcat")
     * })
     */
    private $idcat;

    public function getIdArticle(): ?int
    {
        return $this->id_article;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImageArticle()
    {
        return $this->imageArticle;
    }

    public function setImageArticle( $imageArticle)
    {
        $this->imageArticle = $imageArticle;

        return $this;
    }


    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQtArticle(): ?int
    {
        return $this->qtArticle;
    }

    public function setQtArticle(int $qtArticle): self
    {
        $this->qtArticle = $qtArticle;

        return $this;
    }

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function setRef(int $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIdcat(): ?Categoriearticle
    {
        return $this->idcat;
    }

    public function setIdcat(?Categoriearticle $idcat): self
    {
        $this->idcat = $idcat;

        return $this;
    }




}
