<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class User
{
    private int $id;
    public string $email;
    private string $password;
    private string $cnp;
    public string $firstName;
    public string $lastName;
    public Collection $roles;
    private Collection $programmes;

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

    public function setPassword(string $password): void
    {
        $this->password = $password;
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

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }

    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function setProgrammes(Collection $programmes): void
    {
        $this->programmes = $programmes;
    }
}