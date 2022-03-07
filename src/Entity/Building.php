<?php

namespace App\Entity;

class Building
{
    private int $id;
    public DateTime $startTime;
    public DateTime $endTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }


    public function setStartTime(DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }


    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }


    public function setEndTime(DateTime $endTime): void
    {
        $this->endTime = $endTime;
    }
}
