<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity
 */
class Club
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_club", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idClub;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_club", type="string", length=30, nullable=false)
     */
    private $nomClub;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_joueurs", type="integer", nullable=false)
     */
    private $nbrJoueurs;

    public function getIdClub(): ?int
    {
        return $this->idClub;
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

    public function getNbrJoueurs(): ?int
    {
        return $this->nbrJoueurs;
    }

    public function setNbrJoueurs(int $nbrJoueurs): self
    {
        $this->nbrJoueurs = $nbrJoueurs;

        return $this;
    }


}
