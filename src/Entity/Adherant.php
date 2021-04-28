<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Adherant
 *
 * @ORM\Table(name="adherant")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AdherantRepository")
 *
 */
class Adherant implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="idA", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ida;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=15, nullable=false)
     * @Assert\NotBlank(message="Nom est obligatoire")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=15, nullable=false)
     * @Assert\NotBlank(message="Prenom est obligatoire")
     */
    private $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="cin", type="integer", nullable=false)
     * @Assert\NotBlank(message="CIN est obligatoire")
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="L'adresse est obligatoire")
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Votre CIN doit avoir {{ limit }} chiffres.",
     *      allowEmptyString = false
     * )
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="nomTerain", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="le Nom de Terrain est obligatoire")
     */
    private $nomterain;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Email est obligatoire")
     * @Assert\Email(message = "Mail '{{ value }}' n'est pas Valide.")
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="numTel", type="integer", nullable=false)
     * @Assert\NotBlank(message="Numero de tel. est obligatoire")
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "votre numéro de tel. doit avoir {{ limit }} chiffres.",
     *      allowEmptyString = false
     * )
     */
    private $numtel;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Mot de Passe est obligatoire")
     */
    private $mdp;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    public function getIda(): ?int
    {
        return $this->ida;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNomterain(): ?String
    {
        return $this->nomterain;
    }

    public function setNomterain(string $nomterain): self
    {
        $this->nomterain = $nomterain;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }
    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */

    public function getRoles()
    {
        return array('ROLE_ADHERANT');
    }

    public function getPassword(): string
    {
        return (string)$this->mdp;
    }
    public function setPassword($mdp)
    {
        $this->mdp = $mdp;

    }

    public function getSalt(){}

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(){}

    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
}
