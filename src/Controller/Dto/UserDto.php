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
     * @param User $object
     * @return User
     */
    public function fromObject($object): User
    {
        $dto = new User();
        $dto->setFirstName($object->getFirstName());
        $dto->setLastName($object->getLastName());
        $dto->setEmail($object->getEmail());
        $dto->setCnp($object->getCnp());
        $dto->setRoles($object->getRoles());

        return $dto;
    }

    /**
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function fromArray(array $data): User
    {
        $dto = new User();
        $dto->setFirstName((string) $data['firstName'])
            ->setLastName((string) $data['lastName'])
            ->setEmail((string) $data['email'])
            ->setPassword((string) $data['password'])
            ->setRoles(['customer']);

        return $dto;
    }
}