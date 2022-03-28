<?php

declare(strict_types=1);

namespace App\Controller\Dto;

use App\Entity\EntityInterface;
use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use DateTime;

class ProgrammeDto implements DtoInterface
{
    private string $name;

    private string $description;

    private DateTime $startTime;

    private DateTime $endTime;

    private bool $isOnline;

    private int $maxParticipants;

    private ?User $trainer;

    private ?Room $room;


    public function fromObject(EntityInterface $object): Programme
    {
        $dto = new Programme();
        $dto->setName($object->getName())
            ->setDescription($object->getDescription())
            ->setStartTime($object->getStartTime())
            ->setEndTime($object->getEndTime())
            ->setIsOnline($object->getIsOnline());

        if (null !== $object->getTrainer()) {
            $dto->setTrainer($object->getTrainer());
        }

        if (null !== $object->getRoom()) {
            $dto->setRoom($object->getRoom());
        }

        return $dto;
    }

    public function fromArray(array $data): Programme
    {
        $dto = new Programme();
        $dto->setName($data['name'])
                ->setDescription($data['description'])
                ->setStartTime(new DateTime($data['startTime'] ?? $data['startDate']))
                ->setEndTime(new DateTime($data['endTime'] ?? $data['endDate']))
                ->setIsOnline((bool) $data['isOnline'])
                ->setMaxParticipants((int) $data['maxParticipants']);

        if (isset($data['trainer'])) {
            $dto->setTrainer($data['trainer']);
        }

        if (isset($data['room'])) {
            $dto->setRoom($data['room']);
        }

        return $dto;
    }

    public function fromObjectCollection(iterable $collection): iterable
    {
        $programmeArrayCollection = [];

        foreach ($collection as $key => $programme) {
            $programmeArrayCollection[] = $this->fromObject($programme);
        }

        return $programmeArrayCollection;
    }

    public function fromArrayCollection(array $collection): array
    {
        $programmeObjectCollection = [];

        foreach ($collection as $programme) {
            $programmeObjectCollection[] = $this->fromArray($programme);
        }

        return $programmeObjectCollection;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProgrammeDto
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): ProgrammeDto
    {
        $this->description = $description;
        return $this;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): ProgrammeDto
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): ProgrammeDto
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): ProgrammeDto
    {
        $this->isOnline = $isOnline;
        return $this;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): ProgrammeDto
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    public function getTrainer(): User
    {
        return $this->trainer;
    }

    public function setTrainer(User $trainer): ProgrammeDto
    {
        $this->trainer = $trainer;
        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): ProgrammeDto
    {
        $this->room = $room;
        return $this;
    }
}
