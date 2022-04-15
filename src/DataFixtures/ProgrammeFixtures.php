<?php

namespace App\DataFixtures;

use App\Factory\ProgrammeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProgrammeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        ProgrammeFactory::createMany(15);
    }
}
