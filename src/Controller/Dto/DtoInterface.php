<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\EntityInterface;

interface DtoInterface
{
    public function fromObject(EntityInterface $object): EntityInterface;

    public function fromArray(array $data): EntityInterface;

    public function fromObjectCollection(iterable $collection): iterable;

    public function fromArrayCollection(array $collection): array;
}
