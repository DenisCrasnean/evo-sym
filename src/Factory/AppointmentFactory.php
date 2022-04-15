<?php

namespace App\Factory;

use App\Controller\Dto\ProgrammeDto;
use App\Controller\Dto\UserDto;
use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Zenstruck\Foundry\ModelFactory;

class AppointmentFactory extends ModelFactory
{
    private ProgrammeRepository $programmeRepository;

    private UserRepository $userRepository;

    private ProgrammeDto $programmeDto;

    private UserDto $userDto;

    public function __construct(UserRepository $userRepository, ProgrammeRepository $programmeRepository, ProgrammeDto $programmeDto, UserDto $userDto)
    {
        parent::__construct();

        $this->programmeRepository = $programmeRepository;
        $this->userRepository = $userRepository;
        $this->programmeDto = $programmeDto;
        $this->userDto = $userDto;
    }

    protected static function getClass(): string
    {
        return Programme::class;
    }

    protected function getDefaults(): array
    {
        $programmes = $this->programmeRepository->findAll();

        return self::faker()->randomElements($programmes);
    }

    protected function initialize(): self
    {
        $programmes = self::getDefaults();

        foreach ($programmes as $programme) {
            $this->afterInstantiate(function () use ($programme) {
                $users = $this->userRepository->findAll();
                $participants = self::faker()->randomElements($users);
                $programmeDto = $this->programmeDto->fromArray($programme);

                $programmeDto->setCustomers(
                        new ArrayCollection(
                            $this->userDto
                                ->fromArrayCollection($participants)
                        )
                    );
            });
        }

        return $this;
    }
}
