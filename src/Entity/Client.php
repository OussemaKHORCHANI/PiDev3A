<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 *
 * @UniqueEntity(fields={"email"}, message="L'Email que vous avez indiqué est deja utlisé !")
 */
class Client implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="idC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("terrain")
     */
    private $idc;

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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="L'Adresse est obligatoire")
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(name="numTelC", type="integer", nullable=false)
     * *@Assert\NotBlank(message="Numero de tel. est obligatoire")
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Votre numéro de tel. doit avoir {{ limit }} chiffres.",
     *      allowEmptyString = false
     * )
     */
    private $numtelc;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Email est obligatoire")
     * @Assert\Email(message = "Mail '{{ value }}' n'est pas Valide.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Mot de Passe est obligatoire")
     * @Assert\EqualTo(propertyPath="confirm_mdp",message="Votre Mot de passe doit etre le meme que vous avez confirmez")
     *
     *
     */
    private $mdp;
    /**
     * @Assert\NotBlank(message="Mot de Passe est obligatoire")
     * @Assert\EqualTo(propertyPath="mdp",message="Vous N'avez pas tapez le meme Mot de Passe")
     *
     *
     */

    public $confirm_mdp;
    protected $captchaCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNumtelc(): ?int
    {
        return $this->numtelc;
    }

    public function setNumtelc(?int $numtelc): self
    {
        $this->numtelc = $numtelc;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }
    public function __toString(): ?string
    {
        return $this->getNom();
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_CLIENT');
    }

    public function getPassword(): string
    {
        return (string)$this->mdp;
    }
    public function setPassword($mdp)
    {
        $this->mdp = $mdp;

    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername() : ?string
    {
       return (string) $this->email;
    }

    public function eraseCredentials(){}
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

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
