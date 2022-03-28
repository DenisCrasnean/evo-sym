<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\EntityInterface;
use App\Entity\User;
use DateTime;

class UserDto implements DtoInterface
{
    public int $id;

    public string $firstName;

    public string $lastName;

    public string $email;

    public string $password;

    public string $cnp;

    public array $roles = [];

    public function fromObject(EntityInterface $object): User
    {
        $dto = new User();
        $dto->setFirstName($object->getFirstName())
            ->setLastName($object->getLastName())
            ->setEmail($object->getEmail())
            ->setPassword($object->getPassword())
            ->setCnp($object->getCnp())
            ->setRoles($object->getRoles() ?? ['ROLE_CUSTOMER']);

        return $dto;
    }

    public function fromArray(array $data): User
    {
        $dto = new User();
        $dto->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setCnp($data['cnp'])
            ->setPassword($data['password'])
            ->setRoles($data['roles'] ?? ['ROLE_CUSTOMER']);

        return $dto;
    }

    public function fromObjectCollection(iterable $collection): iterable
    {
        $userObjectCollection = [];

        foreach ($collection as $user) {
            $userObjectCollection[] = $this->fromObject($user);
        }

        return $userObjectCollection;
    }

    public function fromArrayCollection(array $collection): array
    {
        $userObjectCollection = [];

        foreach ($collection as $user) {
            $userObjectCollection[] = $this->fromArray($user);
        }

        return $userObjectCollection;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): UserDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): UserDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): UserDto
    {
        $this->password = $password;
        return $this;
    }

    public function getCnp(): string
    {
        return $this->cnp;
    }

    public function setCnp(string $cnp): UserDto
    {
        $this->cnp = $cnp;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): UserDto
    {
        $this->roles = $roles;
        return $this;
    }
}
