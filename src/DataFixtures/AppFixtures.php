<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::createMany(10);
        QuestionFactory::createMany(10);
        QuestionFactory::new()->notPublished()->many(5)->create();
        AnswerFactory::createMany(100);
        AnswerFactory::new()->needsApproval()->many(20)->create();
        AnswerFactory::new()->spam()->many(10)->create();
    }
}
