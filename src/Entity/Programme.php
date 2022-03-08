<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class Programme
{
    public string $name;
    public string $description;
    private \DateTime $startDate;
    private \DateTime $endDate;
    private User $trainer;
    private Room $room;
    private ?Collection $customers;
    private bool $isOnline;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getTrainer(): User
    {
        return $this->trainer;
    }

    public function setTrainer(User $trainer): void
    {
        $this->trainer = $trainer;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    public function getCustomers(): ?Collection
    {
        return $this->customers;
    }

    public function setCustomers(?Collection $customers): void
    {
        $this->customers = $customers;
    }

    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }
}