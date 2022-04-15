<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProgrammeRepository::class)
 * @ORM\Table(name="programme")
 */
class Programme implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     * @Groups("api:programme:fetchAll")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api:programme:fetchAll")
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("api:programme:fetchAll")
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("api:programme:fetchAll")
     */
    private DateTime $startTime;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("api:programme:fetchAll")
     */
    private DateTime $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="trainer_id", referencedColumnName="id", nullable=true)
     * @Groups("api:programme:fetchAll")
     */
    private ?User $trainer;

    /**
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn (name="room_id", referencedColumnName="id", nullable=true)
     * @Groups("api:programme:fetchAll")
     */
    private ?Room $room;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="programmes")
     * @ORM\JoinTable(name="programmes_customers")
     * @Groups("api:programme:fetchAll")
     */
    private Collection $customers;
    /**
     * @ORM\Column(type="integer")
     * @Groups("api:programme:fetchAll")
     */
    private int $maxParticipants;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("api:programme:fetchAll")
     */
    private bool $isOnline;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Programme
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Programme
    {
        $this->description = $description;

        return $this;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): Programme
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): Programme
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): Programme
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): Programme
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param ArrayCollection|Collection $customers
     */
    public function setCustomers($customers): Programme
    {
        $this->customers = $customers;

        return $this;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): Programme
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(?bool $isOnline): Programme
    {
        $this->isOnline = $isOnline;

        return $this;
    }
}
