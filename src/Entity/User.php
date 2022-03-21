<?php

namespace App\Entity;

use App\Validator as MyAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User implements EntityInterface
{
    public const ROLE_USER = 'ROLE_CUSTOMER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLES = ['ROLE_USER', 'ROLE_ADMIN'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     */
    public string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @MyAssert\Password
     */
    public string $password;

    /**
     * @ORM\Column(type="string", length=13, options={"fixed" = true})
     * @MyAssert\Cnp
     */
    public string $cnp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/[A-Z][a-z]+/")
     */
    public ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/[A-Z][a-z]+/")
     */
    public ?string $lastName;

    /**
     * @ORM\Column(type="json")
     */
    public array $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="Programme", mappedBy="customers")
     */
    private Collection $programmes;

    /**
     * @param Collection $programmes
     **/
    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCnp(): string
    {
        return $this->cnp;
    }

    public function setCnp(?string $cnp): User
    {
        $this->cnp = $cnp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return $this
     */
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
     *
     * @return User
     */
    public function setProgrammes($programmes): ArrayCollection
    {
        $this->programmes = $programmes;

        return $this;
    }
}
