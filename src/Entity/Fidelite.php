<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fidelite
 *
 * @ORM\Table(name="fidelite", indexes={@ORM\Index(name="fk_Client", columns={"idClient"})})
 * @ORM\Entity
 */
class Fidelite
{
    /**
     * @var int
     *
     * @ORM\Column(name="idfidelite", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfidelite;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="idC")
     * })
     */
    private $idclient;

    public function getIdfidelite(): ?int
    {
        return $this->idfidelite;
    }

    public function getIdclient(): ?Client
    {
        return $this->idclient;
    }

    public function setIdclient(?Client $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }


}
