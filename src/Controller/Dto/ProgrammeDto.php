<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\Programme;
use Symfony\Component\Serializer\Annotation as Serializer;

class ProgrammeDto extends AbstractDto
{
    /**
     * @Serializer\Type("string")
     */
    private string $name;

    /**
     * @Serializer\Type("string")
     */
    private string $description;

    /**
     * @Serializer\Type("DateTime")
     */
    private \DateTime $startDate;

    /**
     * @Serializer\Type("DateTime")
     */
    private \DateTime $endDate;

    /**
     * @Serializer\Type("boolean")
     */
    private bool $isOnline;

    /**
     * @param Programme $object
     * @return Programme
     */
    public function fromObject($object): Programme
    {
        $dto = new Programme();
        $dto->setName($object->getName())
            ->setDescription($object->getDescription())
            ->setStartDate(new \DateTime($object->getStartDate()))
            ->setEndDate(new \DateTime($object->getEndDate()))
            ->setIsOnline($object->getIsOnline());

        return $dto;
    }

    /**
     * @param array $data
     * @return Programme
     * @throws \Exception
     */
    public function fromArray(array $data): Programme
    {
       $programme = new Programme();
       $programme->setName((string) $data['name'])
           ->setDescription((string) $data['description'])
           ->setStartDate(new \DateTime($data['startDate']))
           ->setEndDate(new \DateTime($data['endDate']))
           ->setIsOnline((bool) $data['isOnline']);

       return $programme;
    }
}
