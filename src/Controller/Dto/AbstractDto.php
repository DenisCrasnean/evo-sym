<?php

declare(strict_types=1);

namespace App\Controller\Dto;

abstract class AbstractDto implements DtoInterface
{
    public function fromArrayCollection(array $data): iterable
    {
        $dto = [];

        foreach ($data as $item) {
            $dto[] = $this->fromArray($item);
        }

        return $dto;
    }

    public function fromObjectCollection(iterable $objects): iterable
    {
        $dto = [];

        foreach ($objects as $object) {
            $dto[] = $this->fromObject($object);
        }

        return $dto;
    }
}