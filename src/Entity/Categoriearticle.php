<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoriearticle
 *
 * @ORM\Table(name="categoriearticle")
 * @ORM\Entity
 */
class Categoriearticle
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcat;

    /**
     * @var string
     *
     * @ORM\Column(name="nomcat", type="string", length=255, nullable=false)
     */
    public $nomcat;

    public function getIdcat(): ?int
    {
        return $this->idcat;
    }

    public function getNomcat(): ?string
    {
        return $this->nomcat;
    }

    public function setNomcat(string $nomcat): self
    {
        $this->nomcat = $nomcat;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->getIdcat();
    }

}
