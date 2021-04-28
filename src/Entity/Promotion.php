<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_promo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("posts:read")
     */
    private $idPromo;

    /**
     * @var int|null
     ** @Assert\Range(
     *      min = 20,
     *      max = 80,
     *      notInRangeMessage = "You must be between {{ min }}% and {{ max }} % ",
     * )
     * @ORM\Column(name="pourcentage", type="integer", nullable=true)
     * @Groups("posts:read")
     */
    private $pourcentage;

    /**
     * @var \DateTime|null
     * @Assert\Range(min = "now", minMessage ="la date doit etre apres la date d'aujourdhui")
     * *Assert\Expression("this.getDateDebut() < this.getDateFin()",message= "verifier les dates ")
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     * @Groups("posts:read")
     */
    private $dateDebut;

    /**
     * @var \DateTime|null
     *Assert\Expression("this.getDateDebut() < this.getDateFin()",message= "verifier les dates ")
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     * @Groups("posts:read")
     */
    private $dateFin;

    public function getIdPromo(): ?int
    {
        return $this->idPromo;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }


}
