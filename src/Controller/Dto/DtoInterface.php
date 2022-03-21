<?php

declare(strict_types=1);

namespace App\Controller\Dto;

interface DtoInterface
{
    public function fromObject(object $object): object;

    public function fromArray(array $data): object;

    public function fromArrayCollection(array $data): iterable;

    public function fromObjectCollection(iterable $objects): iterable;
}
