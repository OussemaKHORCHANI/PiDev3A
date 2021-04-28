<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int

     * @ORM\Column(name="id_categorie", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategorie;

    /**
     * @var string
     *@Assert\NotBlank
     * message= " ce champs est obligatoire "
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
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
    public function __toString(): string
    {
        return $this->getType();
    }

}
