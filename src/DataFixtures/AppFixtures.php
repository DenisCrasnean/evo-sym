<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ProgrammeFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(50);
        ProgrammeFactory::createMany(50);

        $manager->flush();
    }
}
