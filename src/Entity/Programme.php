<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Programme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public ?string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="trainer_id", referencedColumnName="id")
     */
    private ?User $trainer;

    /**
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    private Room $room;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="programmes")
     * @ORM\JoinTable(name="programmes_customers")
     */
    private Collection $customers;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isOnline;

    /**
     * @param Collection $customers
     **/
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

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): Programme
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): Programme
    {
        $this->endDate = $endDate;

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

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): Programme
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

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(?bool $isOnline): Programme
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function addCustomer(User $customer): self
    {
        if ($this->customers->contains($customer)) {
            return $this;
        }

        $this->customers->add($customer);
        $customer->addProgramme($this);

        return $this;
    }
}
