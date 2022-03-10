<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\Dto\UserDto;

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
     */
    public ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $cnp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $lastName;

    /**
     * @ORM\Column(type="json")
     */
    public array $roles;

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

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
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