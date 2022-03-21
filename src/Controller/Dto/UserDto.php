<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\User;
use Symfony\Component\Serializer\Annotation as Serializer;

class UserDto extends AbstractDto
{
    /**
     * @Serializer\Type("integer")
     */
    public int $id;

    /**
     * @Serializer\Type("string")
     */
    public string $firstName;

    /**
     * @Serializer\Type("string")
     */
    public string $lastName;

    /**
     * @Serializer\Type("string")
     */
    public string $email;

    /**
     * @Serializer\Type("string")
     */
    public string $password;

    /**
     * @Serializer\Type("string")
     */
    public string $cnp;

    /**
     * @Serializer\Type("array<T>")
     */
    public array $roles = [];

    /**
     * @param User $entity
     */
    public function fromObject($entity): User
    {
        $dto = new User();
        $dto->setFirstName($entity->getFirstName());
        $dto->setLastName($entity->getLastName());
        $dto->setEmail($entity->getEmail());
        $dto->setCnp($entity->getCnp());
        $dto->setRoles($entity->getRoles());

        return $dto;
    }

    public function fromArray(array $data): User
    {
        $dto = new User();
        $dto->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRoles($data['roles'] ?? ['ROLE_CUSTOMER']);

        return $dto;
    }
}
