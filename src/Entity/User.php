<?php

namespace App\Entity;

use App\Controller\Dto\UserDto;
use App\Validator as MyAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User
{
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
    public ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @MyAssert\Password
     */
    public ?string $password;

    /**
     * @ORM\Column(type="string", length=13, options={"fixed" = true})
     * @MyAssert\Cnp
     */
    public ?string $cnp;

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCnp(): string
    {
        return $this->cnp;
    }

    public function setCnp(string $cnp): void
    {
        $this->cnp = $cnp;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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

    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function setProgrammes(Collection $programmes): void
    {
        $this->programmes = $programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if ($this->programmes->contains($programme)) {
            return $this;
        }

        $this->programmes->add($programme);
        $programme->addCustomer($this);

        return $this;
    }

    public static function createFromDto(UserDto $userDto): self
    {
        $user = new self();
        $user->setRoles(['customer']);
        $user->cnp = $userDto->cnp;
        $user->firstName = $userDto->firstName;
        $user->lastName = $userDto->lastName;
        $user->email = $userDto->email;
        $user->setPassword($userDto->password);

        return $user;
    }
}
