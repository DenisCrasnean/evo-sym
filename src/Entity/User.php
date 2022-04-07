<?php

namespace App\Entity;

use App\Security\Token\PasswordResetToken;
use App\Validator as MyAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    public const ROLE_USER = 'ROLE_CUSTOMER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLES = ['ROLE_CUSTOMER', 'ROLE_ADMIN'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @MyAssert\Password
     */
    private string $password;

    /**
     * One User has many Reset Password Tokens.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\PasswordReset", mappedBy="user")
     */
    private ?Collection $passwordResets;

    /**
     * @ORM\Column(type="string", length=13, options={"fixed" = true})
     * @MyAssert\Cnp
     */
    private string $cnp;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Regex("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/")
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/[A-Z][a-z]+/")
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/[A-Z][a-z]+/")
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="json")
     */
    public array $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="Programme", mappedBy="customers")
     */
    private Collection $programmes;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->passwordResets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCnp(): string
    {
        return $this->cnp;
    }

    public function setCnp(string $cnp): User
    {
        $this->cnp = $cnp;

        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): User
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordResets(): ?Collection
    {
        return $this->passwordResets;
    }

    public function setPasswordResets(Collection $passwordResets): User
    {
        $this->passwordResets = $passwordResets;

        return $this;
    }

    public function getPasswordResetToken(): string
    {
        return $this->getPasswordResets()->last()->getToken();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getProgrammes()
    {
        return $this->programmes;
    }

    /**
     * @param ArrayCollection|Collection $programmes
     */
    public function setProgrammes($programmes): User
    {
        $this->programmes = $programmes;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
