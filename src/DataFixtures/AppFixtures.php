<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        QuestionFactory::createMany(10);
        QuestionFactory::new()->notPublished()->many(5)->create();
        UserFactory::createMany(10);
    }
}
