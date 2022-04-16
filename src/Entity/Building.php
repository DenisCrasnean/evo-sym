<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Building implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $startTime;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $endTime;

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
