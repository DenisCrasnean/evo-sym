<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\EntityInterface;

interface DtoInterface
{
    public function fromObject(EntityInterface $entity): EntityInterface;

    public function fromArray(array $data): EntityInterface;

    public function fromArrayCollection(array $data): iterable;

    public function fromObjectCollection(iterable $objects): iterable;
}
