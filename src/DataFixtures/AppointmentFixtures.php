<?php

namespace App\DataFixtures;

use App\Factory\AppointmentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        AppointmentFactory::createMany(150);
    }
}
