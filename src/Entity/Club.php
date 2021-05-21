<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
class Club
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_club", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idClub;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_club", type="string", length=30, nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 10,
     *      minMessage = "Le nom du club doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom du club ne doit pas dépasser {{ limit }} caractères"
     *
     * )
     */
    private $nomClub;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_joueurs", type="integer", nullable=false)
     * @Assert\NotBlank(
     * message = "remplissez le champ SVP"
     * )
     * @Assert\LessThan(
     *     value =12 ,
     *     message = "Le nombre de joueurs ne doit pas être supérieur à  11 "
     * )
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

    public function __toString()
    {
        return (string) $this->getNomClub();
    }


}
